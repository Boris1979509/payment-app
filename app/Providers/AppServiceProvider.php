<?php

namespace App\Providers;

use App\Services\Payment\PaymentCodeGenerator\PaymentCodeGenerator;
use App\Services\Payment\PaymentCodeGenerator\UniquePaymentCodeGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance(PaymentCodeGenerator::class, UniquePaymentCodeGenerator::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
