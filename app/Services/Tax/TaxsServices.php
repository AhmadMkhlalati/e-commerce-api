<?php

namespace App\Services\Tax;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;

class TaxsServices{

    public static function deleteRelatedTaxComponents(Tax $tax){

        $taxComponents = $tax->taxComponents();
        if(!$taxComponents->exists()){
            return ;
        }
        $taxComponentsId= $taxComponents->pluck('id');
        TaxComponent::destroy($taxComponentsId);

    }

    public static function createComponentsForTax($components, $tax){
        $componentsArray=array();
        foreach ($components as $key => $value){
            $componentsArray[$key]["component_tax_id"] = $value;
            $componentsArray[$key]["tax_id"] = $tax->id;
            $componentsArray[$key]["sort"] = $key+1;
        }

        TaxComponent::insert($componentsArray);
    }

    public static function calculateTaxAmountFromNonTTCPrice(float $price,int $taxId, $allTaxes, $allTaxComponents)
    {
        $taxAmount = 0;
        $tax = $allTaxes[$taxId];
        if (!$tax['is_complex']) {
            $taxAmount = ($price * $tax['percentage'] / 100);
            echo "Simple [{$tax['percentage']}%]: {$taxAmount}<br/>";
            return $taxAmount;
        }
        $components = getTaxComponent($taxId, $allTaxes, $allTaxComponents);
        if ($tax['complex_behavior'] == 'after') {
            foreach ($components as $componentTax) {
                $componentTaxAmount = self::calculateTaxAmountFromNonTTCPrice($price, $componentTax['id'], $allTaxes, $allTaxComponents);
                $taxAmount += $componentTaxAmount;
                $price += $componentTaxAmount; //so second componene will get tax include the previous tax
                echo "Complex After [{$componentTax['percentage']}%]: {$componentTaxAmount}<br/>";
            }
        } else {
            foreach ($components as $componentTax) {
                $componentTaxAmount = self::calculateTaxAmountFromNonTTCPrice($price, $componentTax['id'], $allTaxes, $allTaxComponents);
                $taxAmount += $componentTaxAmount;
                echo "Complex Combine [{$componentTax['percentage']}%]: {$componentTaxAmount}<br/>";
            }
        }
        return $taxAmount;
    }


}





