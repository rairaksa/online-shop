<?php

namespace App\Providers;

use App\Onlineshop\Client;
use Illuminate\Support\ServiceProvider;

class OnlineshopServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            $config = $app['config']['onlineshop'];

            return new Client($config['base_url']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
