<?php

namespace App\Services\Discounts;


class DiscountsServices {

    /**
     * @param $arrays
     * @return array
     */
    public static function extractProductsFromMultiArray(Array $arrays): array
    {
        $products = [];
        foreach($arrays as $productsArray){
            foreach ($productsArray as $product){
                $products[] =  $product;
            }
        }

        return $products;

    }

    public static function filterProducts( $tagsProducts, $brandsProducts, $categorySingleProducts, $categoryMultipleProducts , $operator){
        if($operator == 'or'){
            $mergedArray = array_merge($tagsProducts,$brandsProducts,$categorySingleProducts,$categoryMultipleProducts);
            return collect($mergedArray)->unique('id');
        }

        $maxArray = max(($tagsProducts), ($brandsProducts), ($categorySingleProducts), ($categoryMultipleProducts));
        $result = [];
        foreach ($maxArray as $array)
            if(in_array($array,$tagsProducts) && in_array($array,$brandsProducts) && in_array($array,$categorySingleProducts) && in_array($array,$categoryMultipleProducts) )
                $result[] = $array;

        return $result;
    }
}




