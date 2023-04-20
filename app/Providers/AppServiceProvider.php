<?php

namespace App\Providers;

use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use App\Models\Discount\Discount;
use App\Models\Field\Field;
use App\Models\Orders\Order;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Settings\Setting;
use App\Models\Tax\Tax;
use App\Models\User\Customer;
use App\Models\User\User;
use App\Observers\Brand\BrandObserver;
use App\Observers\Category\CategoryObserver;
use App\Observers\Currency\CurrencyHistoryObserver;
use App\Observers\Currency\CurrencyObserver;
use App\Observers\Customer\CustomerObserver;
use App\Observers\Discount\DiscountObserver;
use App\Observers\Fields\FieldsObserver;
use App\Observers\Order\OrderObserver;
use App\Observers\Price\PriceObserver;
use App\Observers\Product\ProductsObserver;
use App\Observers\Tax\TaxObserver;
use App\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Observers\Settings\SettingObserver;
use App\Observers\User\UserObserver;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);
        $classes = [
            [
                'model' => Setting::class,
                'observer' => SettingObserver::class,
            ],
            [
                'model' => CurrencyHistory::class,
                'observer' => CurrencyHistoryObserver::class,
            ],
            [
                'model' => Currency::class,
                'observer' => CurrencyObserver::class,
            ],
            [
                'model' => Brand::class,
                'observer' => BrandObserver::class,
            ],
            [
                'model' => Category::class,
                'observer' => CategoryObserver::class,
            ],
            [
                'model' => Customer::class,
                'observer' => CustomerObserver::class,
            ],
            [
                'model' => Discount::class,
                'observer' => DiscountObserver::class,
            ],
            [
                'model' => Field::class,
                'observer' => FieldsObserver::class,
            ],
            [
                'model' => Field::class,
                'observer' => FieldsObserver::class,
            ],
            [
                'model' => Order::class,
                'observer' => OrderObserver::class,
            ],
            [
                'model' => Price::class,
                'observer' => PriceObserver::class,
            ],
            [
                'model' => Product::class,
                'observer' => ProductsObserver::class,
            ],
            [
                'model' => Product::class,
                'observer' => ProductsObserver::class,
            ],
            [
                'model' => Tax::class,
                'observer' => TaxObserver::class,
            ],
            [
                'model' => User::class,
                'observer' => UserObserver::class,
            ],
        ];

        foreach ($classes as $class){
            call_user_func($class['model'] . '::observe',$class['observer']);
        }

        Setting::observe(SettingObserver::class);
        User::observe(UserObserver::class);

        // Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
        //     $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

        //     return new LengthAwarePaginator(
        //         $this->forpage($page, $perPage),
        //         $total ?: $this->count(),
        //         $perPage,
        //         $page,
        //         [
        //             'path' => LengthAwarePaginator::resolveCurrentPath(),
        //             'pageName' => $pageName,
        //         ]
        //     );
        // });


    }
}
