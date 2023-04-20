<?php

namespace App\Services\Field;

use App\Models\Field\Field;
use App\Models\Field\FieldValue;

class FieldService{

    public static function deleteRelatedfieldValues(Field $field){
        $fieldValue = $field->fieldValue();
        if(!$fieldValue->exists()){
            return ;
        }
        $fieldValueId= $fieldValue->pluck('id');
        FieldValue::destroy($fieldValueId);

    }

    public static function addFieldValuesToField(array $fieldValues, Field $field){
            $fieldsValuesArray = [];
            foreach ($fieldValues as $key => $value){
                $fieldsValuesArray[$key]['field_id'] = $field->id;
                $fieldsValuesArray[$key]['value'] = json_encode($value['value']);
            }
            return $check = FieldValue::insert($fieldsValuesArray);


    }

}





