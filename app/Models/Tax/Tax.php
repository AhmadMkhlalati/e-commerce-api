<?php

namespace App\Models\Tax;

use App\Models\Price\Price;
use App\Models\Tax\TaxComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Brand\Brand;
use Spatie\Translatable\HasTranslations;

class Tax extends MainModel
{
    use HasFactory, HasTranslations;

    protected array $translatable = ['name'];
    protected $table = 'taxes';
    protected $guard_name = 'web';
    protected $fillable = [
        'id', // added just for when creating an object form this class while working with Orders in OrderService, so we don't use more unnecessary queries
        'name',
        'percentage',
        'complex_behavior',
        'is_complex',

    ];
    public static $taxTypes =  'combine,after_other';
    public static $minimumTaxPercentage = 0;
    public static $maximumTaxPercentage = 100;

    public function taxComponents()
    {
        return $this->hasMany(TaxComponent::class, 'tax_id');
    }

    public function taxComponentsParents()
    {
        return $this->hasMany(TaxComponent::class, 'component_tax_id');
    }

    public function cateogry()
    {
        return $this->hasMany(Category::class, 'category_id');
    }
    public function product(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'tax_id');
    }
    public function tax()
    {
        return $this->hasMany(Tax::class, 'tax_id');
    }
    public function brand()
    {
        return $this->hasMany(Brand::class, 'brand_id');
    }

    /**
     * @throws \Exception
     */
    public function getComplexPrice(float $price, array $allTaxComponents = [], $allTaxes = []): float
    {

        if (!$this->is_complex) {
            throw new \Exception('Calling getComplexPrice on non-complex tax');
        }

        $allTaxComponents = count($allTaxComponents) != 0 ? $allTaxComponents : TaxComponent::all()->toArray();
        $allTaxes = count($allTaxes) != 0 ? $allTaxes : Tax::all()->toArray();
        $allTaxes = collect($allTaxes);
        $resultantTaxRate = 0;

        if ($this->complex_behavior == 'combine') {
            $totalTax =  ($this->getComplexCombinePrice($allTaxes, $allTaxComponents, 0, $price));
            $resultantTaxRate += ($totalTax / 100) * $price;
        } else {
            $this->getComplexAfterOtherPrice($price, $allTaxes, $allTaxComponents, $resultantTaxRate);
        }

        return $resultantTaxRate;
    }

    private function getComplexCombinePrice($allTaxes, $allTaxComponents, $initialTax, $price): float
    {
        $neededTaxComponents = collect($allTaxComponents)->where('tax_id', $this->id)->toArray();
        foreach ($neededTaxComponents as $neededTaxComponent) {
            //            $totalTax = 0.0;
            //            $modelTaxComponent = collect($allTaxes)->where('id',$neededTaxComponent['component_tax_id'])->first();
            $tax = $allTaxes->where('id', $neededTaxComponent['component_tax_id'])->first();
            $tax = new Tax($tax);

            if (is_null($tax)) {
                continue;
            }
            if ($tax->is_complex) {
                $initialTax += $tax->getComplexPrice($price, $allTaxComponents, $allTaxes);
            } else {
                $initialTax += $tax['percentage'];
            }
        }
        return $initialTax;
    }

    private function getComplexAfterOtherPrice(&$price, $allTaxes, $allTaxComponents, &$initialTax)
    {
        $neededTaxComponents = collect($allTaxComponents)->where('tax_id', $this->id)->toArray();
        foreach ($neededTaxComponents as $key => $neededTaxComponent) {

            $neededTaxComponentObject = $allTaxes->where('id', $neededTaxComponent['component_tax_id'])->first();
            $neededTaxComponentObject = new Tax($neededTaxComponentObject);

            if ($neededTaxComponentObject->is_complex) {
                $tempTax = $neededTaxComponentObject->getComplexPrice($price, $allTaxComponents, $allTaxes);
            } else {
                $tempTax = ($neededTaxComponentObject['percentage'] / 100) * $price;
            }
            $price += $tempTax;
            $initialTax += $tempTax;
        }
    }
}


//namespace App\Models\Tax;
//
//use App\Models\Price\Price;
//use App\Models\Tax\TaxComponent;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Models\MainModel;
//use App\Models\Category\Category;
//use App\Models\Product\Product;
//use App\Models\Brand\Brand;
//use Spatie\Translatable\HasTranslations;
//
//class Tax extends MainModel
//{
//    use HasFactory, HasTranslations;
//
//    protected array $translatable = ['name'];
//    protected $table = 'taxes';
//    protected $guard_name = 'web';
//    protected $fillable = [
//        'id', // added just for when creating an object form this class while working with Orders in OrderService, so we don't use more unnecessary queries
//        'name',
//        'percentage',
//        'complex_behavior',
//        'is_complex',
//
//    ];
//
//    public function taxComponents()
//    {
//        return $this->hasMany(TaxComponent::class, 'tax_id');
//    }
//
//    public function cateogry()
//    {
//        return $this->hasMany(Category::class, 'category_id');
//    }
//
//    public function product()
//    {
//        return $this->hasMany(Product::class, 'tax_id');
//    }
//
//    public function tax()
//    {
//        return $this->hasMany(Tax::class, 'tax_id');
//    }
//
//    public function brand()
//    {
//        return $this->hasMany(Brand::class, 'brand_id');
//    }
//
//    /**
//     * @throws \Exception
//     */
//    public function getComplexPrice(float $price, array $allTaxComponents = [], $allTaxes = []): float
//    {
//
//        if (!$this->is_complex) {
//            throw new \Exception('Calling getComplexPrice on non-complex tax');
//        }
//
//        $allTaxComponents = count($allTaxComponents) != 0 ? $allTaxComponents : TaxComponent::all()->toArray();
//        $allTaxes = count($allTaxes) != 0 ? $allTaxes : Tax::all()->toArray();
//        $allTaxes = collect($allTaxes);
//
//        $neededTaxComponents = collect($allTaxComponents)->where('tax_id', $this->id)->toArray();
//
//        $resultantTaxRate = 0.0;
//        $totalTax = 0.0;
//        if ($this->complex_behavior == 'combine') {
//            foreach ($neededTaxComponents as $neededTaxComponent) {
//
//                $modelTaxComponent = collect($allTaxes)->where('id', $neededTaxComponent['component_tax_id'])->first();
//                $modelTaxComponent = new Tax($modelTaxComponent);
//                $tax = $allTaxes->where('id', $neededTaxComponent['component_tax_id'])->first();
//                if (is_null($tax)) {
//                    continue;
//                }
//                if ($modelTaxComponent->is_complex) {
//                    $totalTax += $modelTaxComponent->getComplexPrice($price, $allTaxComponents, $allTaxes);
//
//                } else {
//                    $totalTax += $tax['percentage'];
//                }
//            }
//            $resultantTaxRate = ($totalTax / 100) * $price;
//
//
//        } else {
//            foreach ($neededTaxComponents as $neededTaxComponent) {
//                $modelTaxComponent = collect($allTaxes)->where('id', $neededTaxComponent['component_tax_id'])->first();
//                $modelTaxComponent = new Tax($modelTaxComponent);
//                $tax = $allTaxes->where('id', $neededTaxComponent['component_tax_id'])->first();
//                if (is_null($tax)) {
//                    continue;
//                }
//                if ($modelTaxComponent->is_complex) {
//                    $tempTax = $modelTaxComponent->getComplexPrice($price, $allTaxComponents, $allTaxes);
//                } else {
//                    $tempTax = ($tax['percentage'] / 100) * $price;
//                }
//                $price += $tempTax;
//
//                $totalTax += $tempTax;
//            }
//            $resultantTaxRate = $totalTax;
//        }
//        return $resultantTaxRate;
//    }
//}
