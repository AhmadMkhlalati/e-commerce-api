<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Label\LabelController;
use App\Models\Brand\Brand;
use App\Models\Country\Country;
use App\Models\Currency\Currency;
use App\Models\Label\Label;
use App\Models\Product\Product;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\Tax\Tax;
use App\Models\Unit\Unit;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\MainController;
use App\Exceptions\FileErrorException;
use App\Models\Product\ProductField;
use App\Models\RolesAndPermissions\CustomPermission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Aramex;
use SoapClient;
use App\Actions\Taxses\CalculateTax;
use App\Models\Category\Category;

class TestController extends MainController
{
    use HasRoles;

    public function getToken()
    {
        return User::first()->createToken('my-token');
    }

    public function test()
    {
        return Product::query()->find(1);
    }


    public function createShipment()
    {
        $params = array(
            'Shipments' => array(
                'Shipment' => array(
                    'Shipper'    => array(
                        'Reference1'     => 'Ref 111111',
                        'Reference2'     => 'Ref 222222',
                        'AccountNumber' => '20016',
                        'PartyAddress'    => array(
                            'Line1'                    => 'Mecca St',
                            'Line2'                 => '',
                            'Line3'                 => '',
                            'City'                    => 'Amman',
                            'StateOrProvinceCode'    => '',
                            'PostCode'                => '',
                            'CountryCode'            => 'Jo'
                        ),
                        'Contact'        => array(
                            'Department'            => '',
                            'PersonName'            => 'Michael',
                            'Title'                    => '',
                            'CompanyName'            => 'Aramex',
                            'PhoneNumber1'            => '5555555',
                            'PhoneNumber1Ext'        => '125',
                            'PhoneNumber2'            => '',
                            'PhoneNumber2Ext'        => '',
                            'FaxNumber'                => '',
                            'CellPhone'                => '07777777',
                            'EmailAddress'            => 'michael@aramex.com',
                            'Type'                    => ''
                        ),
                    ),

                    'Consignee'    => array(
                        'Reference1'    => 'Ref 333333',
                        'Reference2'    => 'Ref 444444',
                        'AccountNumber' => '',
                        'PartyAddress'    => array(
                            'Line1'                    => '15 ABC St',
                            'Line2'                    => '',
                            'Line3'                    => '',
                            'City'                    => 'Dubai',
                            'StateOrProvinceCode'    => '',
                            'PostCode'                => '',
                            'CountryCode'            => 'AE'
                        ),

                        'Contact'        => array(
                            'Department'            => '',
                            'PersonName'            => 'Mazen',
                            'Title'                    => '',
                            'CompanyName'            => 'Aramex',
                            'PhoneNumber1'            => '6666666',
                            'PhoneNumber1Ext'        => '155',
                            'PhoneNumber2'            => '',
                            'PhoneNumber2Ext'        => '',
                            'FaxNumber'                => '',
                            'CellPhone'                => '',
                            'EmailAddress'            => 'mazen@aramex.com',
                            'Type'                    => ''
                        ),
                    ),

                    'ThirdParty' => array(
                        'Reference1'     => '',
                        'Reference2'     => '',
                        'AccountNumber' => '',
                        'PartyAddress'    => array(
                            'Line1'                    => '',
                            'Line2'                    => '',
                            'Line3'                    => '',
                            'City'                    => '',
                            'StateOrProvinceCode'    => '',
                            'PostCode'                => '',
                            'CountryCode'            => ''
                        ),
                        'Contact'        => array(
                            'Department'            => '',
                            'PersonName'            => '',
                            'Title'                    => '',
                            'CompanyName'            => '',
                            'PhoneNumber1'            => '',
                            'PhoneNumber1Ext'        => '',
                            'PhoneNumber2'            => '',
                            'PhoneNumber2Ext'        => '',
                            'FaxNumber'                => '',
                            'CellPhone'                => '',
                            'EmailAddress'            => '',
                            'Type'                    => ''
                        ),
                    ),

                    'Reference1'                 => 'Shpt 0001',
                    'Reference2'                 => '',
                    'Reference3'                 => '',
                    'ForeignHAWB'                => 'ABC 000111',
                    'TransportType'                => 0,
                    'ShippingDateTime'             => time(),
                    'DueDate'                    => time(),
                    'PickupLocation'            => 'Reception',
                    'PickupGUID'                => '',
                    'Comments'                    => 'Shpt 0001',
                    'AccountingInstrcutions'     => '',
                    'OperationsInstructions'    => '',

                    'Details' => array(
                        'Dimensions' => array(
                            'Length'                => 10,
                            'Width'                    => 10,
                            'Height'                => 10,
                            'Unit'                    => 'cm',

                        ),

                        'ActualWeight' => array(
                            'Value'                    => 0.5,
                            'Unit'                    => 'Kg'
                        ),

                        'ProductGroup'             => 'EXP',
                        'ProductType'            => 'PDX',
                        'PaymentType'            => 'P',
                        'PaymentOptions'         => '',
                        'Services'                => '',
                        'NumberOfPieces'        => 1,
                        'DescriptionOfGoods'     => 'Docs',
                        'GoodsOriginCountry'     => 'Jo',

                        'CashOnDeliveryAmount'     => array(
                            'Value'                    => 0,
                            'CurrencyCode'            => ''
                        ),

                        'InsuranceAmount'        => array(
                            'Value'                    => 0,
                            'CurrencyCode'            => ''
                        ),

                        'CollectAmount'            => array(
                            'Value'                    => 0,
                            'CurrencyCode'            => ''
                        ),

                        'CashAdditionalAmount'    => array(
                            'Value'                    => 0,
                            'CurrencyCode'            => ''
                        ),

                        'CashAdditionalAmountDescription' => '',

                        'CustomsValueAmount' => array(
                            'Value'                    => 0,
                            'CurrencyCode'            => ''
                        ),

                        'Items'                 => array()
                    ),
                ),
            ),

            'ClientInfo' => array(
                "UserName" => 'testingapi@aramex.com',
                "Password" => 'R123456789$r',
                "AccountNumber" => "20016",
                "AccountPin" => "331421",
                "AccountEntity" => "AMM",
                "AccountCountryCode" => "JO",
                "Version" => '1.0'
            ),

            'Transaction'             => array(
                'Reference1'            => '001',
                'Reference2'            => '',
                'Reference3'            => '',
                'Reference4'            => '',
                'Reference5'            => '',
            ),
            'LabelInfo'                => array(
                'ReportID'                 => 9201,
                'ReportType'            => 'URL',
            ),
        );

        $params['Shipments']['Shipment']['Details']['Items'][] = array(
            'PackageType'     => 'Box',
            'Quantity'        => 1,
            'Weight'        => array(
                'Value'        => 0.5,
                'Unit'        => 'Kg',
            ),
            'Comments'        => 'Docs',
            'Reference'        => ''
        );
        $soapClient = new SoapClient(asset('shipping.wsdl'));
        $auth_call = $soapClient->CreateShipments($params);
        return $auth_call;
    }
}
