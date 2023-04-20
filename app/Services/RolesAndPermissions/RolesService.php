<?php

namespace App\Services\RolesAndPermissions;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesService {

    public static function givePermissionToParentRoleAndChildren(array|Permission $permissions , CustomRole $roles) {
        return $roles->allChildren();

    }

    public static function getRoleChildren(int | Role $role, $flatten= false) : Array
    {
        // got all the roles
        $allRoles = CustomRole::all(); //get all roles info

        // we passed the main role that we want to get its children along with the roles and there children
        $roleChildren = self::generateChildrenForAllRoles($allRoles);

        //if the given data was numeric then take it as the roleId if not then take the id of the passed object
        $roleId = (is_numeric($role) ? $role : $role->id);


        return self::drawRoleChildren($roleId, $roleChildren,!$flatten, $allRoles);
    }

    public static function filterPermissionsAccordingToParentPermissions(Array $parentPermissions,Array $permissions): Array {
        $notAllowedPermissions = array_diff($permissions, $parentPermissions);
        return collect($permissions)->diff($notAllowedPermissions)->all();
    }

    private static function generateChildrenForAllRoles($allRoles):Array {
        $roleChildren = [];
        foreach($allRoles as $currentRole){
            // get the parent ID if there is any if not set to 0, so the values of the first index `0` do not have any parents
            $parentId = ($currentRole->parent_id ?? 0);

            // if the index was not set we just set it.
            if(!isset($roleChildren[$parentId])){
                $roleChildren[$parentId] = [];
            }
            // under the given index we add a new value the new child of that index
            $roleChildren[$parentId][] = $currentRole->id;
        }

        return $roleChildren;

    }

    private static function drawRoleChildren(int $parentRoleId, array $allRolesID ,$isMultiLevel = false, $allRoles): array{ //with levels
        if(empty($allRolesID[$parentRoleId])){
            return [];
        }

        foreach($allRolesID[$parentRoleId] as $childRoleId){
            if($isMultiLevel){
                $childRoles[$childRoleId] = ['data' => $allRoles->find($childRoleId), 'children' => []];
                $childRoles[$childRoleId]['children'] = self::drawRoleChildren($childRoleId, $allRolesID, $isMultiLevel,$allRoles);
            }
            else{
                $childRoles[] = $childRoleId;
                $childRoles = array_merge($childRoles, self::drawRoleChildren($childRoleId, $allRolesID, $isMultiLevel,$allRoles));
            }
        }
        return $childRoles;
    }

    private static function createSinglePermssion(String $name,Int $parentId=null){

        return CustomPermission::create([
            'name' => $name,
            'parent_id' => $parentId,
            'guard_name' => 'web'

        ]);

    }

    private static function createSingalRole(String $name,Int $parentId=null){

        return CustomRole::create([
            'name' => $name,
            'parent_id' => $parentId,
            'guard_name' => 'web'
        ]);

    }

    public static function createRoles(){
        CustomRole::query()->truncate();

        self::createSingalRole('admin');
        self::createSingalRole('dev');

        $chefEmployee = self::createSingalRole('chef_employee');
        self::createSingalRole('chef_employee1',$chefEmployee->id);

    }

    public static function createPermissions(){
        CustomPermission::query()->truncate();

        //User Permission
        $parentCountry= self::createSinglePermssion('UsersController');
        self::createSinglePermssion('UsersController@index',$parentCountry->id );
        self::createSinglePermssion('UsersController@store',$parentCountry->id );
        self::createSinglePermssion('UsersController@show',$parentCountry->id );
        self::createSinglePermssion('UsersController@update',$parentCountry->id );
        self::createSinglePermssion('UsersController@destroy',$parentCountry->id );
        //End of User Permission

       //Country Permission
        $parentCountry= self::createSinglePermssion('CountryController');
       self::createSinglePermssion('CountryController@index',$parentCountry->id );
       self::createSinglePermssion('CountryController@store',$parentCountry->id );
       self::createSinglePermssion('CountryController@show',$parentCountry->id );
       self::createSinglePermssion('CountryController@update',$parentCountry->id );
       self::createSinglePermssion('CountryController@destroy',$parentCountry->id );
       //End of Country Permission

        //Coupon Permission
        $parentCoupon= self::createSinglePermssion('CouponsController');
        self::createSinglePermssion('CouponsController@index',$parentCoupon->id );
        self::createSinglePermssion('CouponsController@store',$parentCoupon->id );
        self::createSinglePermssion('CouponsController@show',$parentCoupon->id );
        self::createSinglePermssion('CouponsController@update',$parentCoupon->id );
        self::createSinglePermssion('CouponsController@destroy',$parentCoupon->id );
        //End of Coupon Permission


        //Currency Permission
          $parentCurrency= self::createSinglePermssion('CurrencyController');
        self::createSinglePermssion('CurrencyController@index',$parentCurrency->id );
        self::createSinglePermssion('CurrencyController@store',$parentCurrency->id );
        self::createSinglePermssion('CurrencyController@show',$parentCurrency->id );
        self::createSinglePermssion('CurrencyController@update',$parentCurrency->id );
        self::createSinglePermssion('CurrencyController@destroy',$parentCurrency->id );
        self::createSinglePermssion('CurrencyController@setCurrencyIsDefault',$parentCurrency->id );
        //End of Currency Permission

       //Currency History Permission
       $parentCountry= self::createSinglePermssion('currency_history_permissions');
           self::createSinglePermssion('currency_history_read',$parentCountry->id );
       //End of Currency History Permission

      //Tag Permission
      $parentTag= self::createSinglePermssion('TagController');
           self::createSinglePermssion('TagController@index',$parentTag->id );
           self::createSinglePermssion('TagController@store',$parentTag->id );
           self::createSinglePermssion('TagController@show',$parentTag->id );
           self::createSinglePermssion('TagController@update',$parentTag->id );
           self::createSinglePermssion('TagController@destroy',$parentTag->id );
      //End of Tag Permission

      //Attribute Permission
      $parentAttribute= self::createSinglePermssion('AttributeController');
           self::createSinglePermssion('AttributeController@index',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@store',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@show',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@update',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@destroy',$parentAttribute->id );
      //End of Attribute Permission

      //Attribute Value Permission
      $parentAttributeValue= self::createSinglePermssion('AttributeValueController');
           self::createSinglePermssion('AttributeValueController@index',$parentAttributeValue->id );
           self::createSinglePermssion('AttributeValueController@store',$parentAttributeValue->id );
           self::createSinglePermssion('AttributeValueController@show',$parentAttributeValue->id );
           self::createSinglePermssion('AttributeValueController@update',$parentAttributeValue->id );
           self::createSinglePermssion('AttributeValueController@destroy',$parentAttributeValue->id );
      //End of Attribute Value Permission

      //Field Permission
      $parentField= self::createSinglePermssion('FieldsController');
           self::createSinglePermssion('FieldsController@index',$parentField->id );
           self::createSinglePermssion('FieldsController@store',$parentField->id );
           self::createSinglePermssion('FieldsController@show',$parentField->id );
           self::createSinglePermssion('FieldsController@update',$parentField->id );
           self::createSinglePermssion('FieldsController@destroy',$parentField->id );
      //End of Field Permission

       //Field Permission
       $parentFieldValue= self::createSinglePermssion('FieldValueController');
           self::createSinglePermssion('FieldValueController@index',$parentFieldValue->id );
           self::createSinglePermssion('FieldValueController@store',$parentFieldValue->id );
           self::createSinglePermssion('FieldValueController@show',$parentFieldValue->id );
           self::createSinglePermssion('FieldValueController@update',$parentFieldValue->id );
           self::createSinglePermssion('FieldValueController@destroy',$parentFieldValue->id );
     //End of Field Permission

      //Language Permission
      $parentLanguage= self::createSinglePermssion('LanguageController');
           self::createSinglePermssion('LanguageController@index',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@store',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@show',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@update',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@destroy',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@setLanguage',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@toggleStatus',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@updateSortValues',$parentLanguage->id );
      //End of Language Permission

      //Label Permission
      $parentLabel= self::createSinglePermssion('LabelController');
           self::createSinglePermssion('LabelController@index',$parentLabel->id );
           self::createSinglePermssion('LabelController@store',$parentLabel->id );
           self::createSinglePermssion('LabelController@show',$parentLabel->id );
           self::createSinglePermssion('LabelController@update',$parentLabel->id );
           self::createSinglePermssion('LabelController@destroy',$parentLabel->id );
      //End of Label Permission

      //Permission Permission
        $parentPermissions= self::createSinglePermssion('PermissionsController');
           self::createSinglePermssion('PermissionsController@index',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@store',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@show',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@update',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@destroy',$parentPermissions->id );
      //End of Permission Permission

      //Role Permission
      $parentRole= self::createSinglePermssion('RolesController');
       self::createSinglePermssion('RolesController@index',$parentRole->id );
       self::createSinglePermssion('RolesController@store',$parentRole->id );
       self::createSinglePermssion('RolesController@show',$parentRole->id );
       self::createSinglePermssion('RolesController@update',$parentRole->id );
       self::createSinglePermssion('RolesController@destroy',$parentRole->id );
     //End of Role Permission

      //Setting Permission
      $parentSetting= self::createSinglePermssion('SettingsController');
       self::createSinglePermssion('SettingsController@index',$parentSetting->id );
       self::createSinglePermssion('SettingsController@store',$parentSetting->id );
       self::createSinglePermssion('SettingsController@show',$parentSetting->id );
       self::createSinglePermssion('SettingsController@update',$parentSetting->id );
       self::createSinglePermssion('SettingsController@destroy',$parentSetting->id );
      //End of Setting Permission

      //Brand Permission
      $parentBrand= self::createSinglePermssion('BrandController');
        self::createSinglePermssion('BrandController@index',$parentBrand->id );
        self::createSinglePermssion('BrandController@store',$parentBrand->id );
        self::createSinglePermssion('BrandController@show',$parentBrand->id );
        self::createSinglePermssion('BrandController@update',$parentBrand->id );
        self::createSinglePermssion('BrandController@destroy',$parentBrand->id );
        self::createSinglePermssion('BrandController@toggleStatus',$parentBrand->id );
        self::createSinglePermssion('BrandController@getAllBrandsSorted',$parentBrand->id );
        self::createSinglePermssion('BrandController@updateSortValues',$parentBrand->id );
      //End of Brand Permission

     //Category Permission
      $parentCategory= self::createSinglePermssion('CategoryController');
        self::createSinglePermssion('CategoryController@index',$parentCategory->id );
        self::createSinglePermssion('CategoryController@store',$parentCategory->id );
        self::createSinglePermssion('CategoryController@show',$parentCategory->id );
        self::createSinglePermssion('CategoryController@update',$parentCategory->id );
        self::createSinglePermssion('CategoryController@destroy',$parentCategory->id );
        self::createSinglePermssion('CategoryController@toggleStatus',$parentCategory->id );
        self::createSinglePermssion('CategoryController@getAllParentsSorted',$parentCategory->id );
        self::createSinglePermssion('CategoryController@getAllChildsSorted',$parentCategory->id );
        self::createSinglePermssion('CategoryController@updateSortValues',$parentCategory->id );
     //End of Category Permission

     //Discount Permission
      $parentDiscount= self::createSinglePermssion('DiscountController');
        self::createSinglePermssion('DiscountController@index',$parentDiscount->id );
        self::createSinglePermssion('DiscountController@store',$parentDiscount->id );
        self::createSinglePermssion('DiscountController@show',$parentDiscount->id );
        self::createSinglePermssion('DiscountController@update',$parentDiscount->id );
        self::createSinglePermssion('DiscountController@destroy',$parentDiscount->id );
   //End of Discount Permission

    //Discount Permission
      $parentDiscountEntity= self::createSinglePermssion('DiscountEntityController');
        self::createSinglePermssion('DiscountEntityController@index',$parentDiscountEntity->id );
        self::createSinglePermssion('DiscountEntityController@store',$parentDiscountEntity->id );
        self::createSinglePermssion('DiscountEntityController@show',$parentDiscountEntity->id );
        self::createSinglePermssion('DiscountEntityController@update',$parentDiscountEntity->id );
        self::createSinglePermssion('DiscountEntityController@destroy',$parentDiscountEntity->id );
    //End of Discount Permission

      //Unit Permission
      $parentUnit= self::createSinglePermssion('UnitController');
        self::createSinglePermssion('UnitController@index',$parentUnit->id );
        self::createSinglePermssion('UnitController@store',$parentUnit->id );
        self::createSinglePermssion('UnitController@show',$parentUnit->id );
        self::createSinglePermssion('UnitController@update',$parentUnit->id );
        self::createSinglePermssion('UnitController@destroy',$parentUnit->id );
    //End of Unit Permission

     //Tax Permission
     $parentTax= self::createSinglePermssion('TaxController');
     self::createSinglePermssion('TaxController@index',$parentTax->id );
     self::createSinglePermssion('TaxController@store',$parentTax->id );
     self::createSinglePermssion('TaxController@show',$parentTax->id );
     self::createSinglePermssion('TaxController@update',$parentTax->id );
     self::createSinglePermssion('TaxController@destroy',$parentTax->id );
    //End of Tax Permission

    //Order Permission
    $parentOrder= self::createSinglePermssion('OrdersController');
    self::createSinglePermssion('OrdersController@index',$parentOrder->id );
    self::createSinglePermssion('OrdersController@store',$parentOrder->id );
    self::createSinglePermssion('OrdersController@show',$parentOrder->id );
    self::createSinglePermssion('OrdersController@update',$parentOrder->id );
    self::createSinglePermssion('OrdersController@destroy',$parentOrder->id );
    //End of Order Permission


    //Price Permission
    $parentPrice= self::createSinglePermssion('PricesController');
    self::createSinglePermssion('PricesController@index',$parentPrice->id );
    self::createSinglePermssion('PricesController@store',$parentPrice->id );
    self::createSinglePermssion('PricesController@show',$parentPrice->id );
    self::createSinglePermssion('PricesController@update',$parentPrice->id );
    //End of Price Permission

    //Price List Permission
    $priceList= self::createSinglePermssion('PricesListController');
    self::createSinglePermssion('PricesListController@show',$priceList->id );
    self::createSinglePermssion('PricesListController@update',$priceList->id );
    //End of Price List Permission

    //Product Permission
    $parentProduct= self::createSinglePermssion('ProductController');
    self::createSinglePermssion('ProductController@index',$parentProduct->id );
    self::createSinglePermssion('ProductController@store',$parentProduct->id );
    self::createSinglePermssion('ProductController@show',$parentProduct->id );
    self::createSinglePermssion('ProductController@update',$parentProduct->id );
    self::createSinglePermssion('ProductController@destroy',$parentProduct->id );
    //End of Product Permission



    }


    // public static function generateRelationStringForRoleChildren(int | Role $role): String
    // {
    //     $old_count = 0;
    //     $relations = "children";
    //     $query = CustomRole::find($role);
    //     $current_count = collect($query->load($relations))->flatten()->count();


    //     while($current_count > $old_count){
    //         $old_count = collect($query->load($relations))->flatten()->count();
    //         $relations .= ".children";
    //         $current_count = collect($query->load($relations))->flatten()->count();
    //     }

    //     return $relations;
    // }


}


