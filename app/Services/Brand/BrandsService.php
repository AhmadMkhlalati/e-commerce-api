<?php

namespace App\Services\Brand;

use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;

class BrandsService {

    /**
     * @throws \Exception
     */
    public static function deleteRelatedBrandFieldsAndLabels(Brand $brand){
        $deletedFields = true;
        $deletedLabels = true;

        if($brand->field()->exists())
            $deletedFields= BrandField::where('brand_id',$brand->id)->delete();

        if($brand->label()->exists())
            $deletedLabels =  BrandLabel::where('brand_id',$brand->id)->delete();

        if(!( $deletedFields || $deletedLabels)){
            throw new \Exception('delete brands fields and labels failed');
        }


    }

    public static function addFieldsToBrands(Brand $brand, array $fields){
        $tobeSavedArray = [];
        foreach ($fields as $key => $field){

            if(gettype($field) == 'string' ){
                $field = (array)json_decode($field);
            }

            if($field["type"]=='select'){
                $tobeSavedArray[$key]["value"] = null;
                if(gettype($field["value"]) == 'array'){
                $tobeSavedArray[$key]["field_value_id"] = $field["value"][0];
                }elseif(gettype($field["value"]) == 'integer'){
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"];
                }
            }
            else{
                $tobeSavedArray[$key]["field_value_id"] = null;
                $tobeSavedArray[$key]["value"] = ($field['value']);
            }
            $tobeSavedArray[$key]["brand_id"] = $brand->id;
            $tobeSavedArray[$key]["field_id"] = $field['field_id'];

        }
        return BrandField::insert($tobeSavedArray);

    }

    public static function addLabelsToBrands(Brand $brand, array $labels){
        $labelsArray=[];
        if(count($labels) <= 0){
            return true;
        }
        foreach ($labels as $key => $label){
            $labelsArray[$key]["brand_id"] = $brand->id;
            $labelsArray[$key]["label_id"] = $label;
        }

        return BrandLabel::insert($labelsArray);
    }

}






