<?php

namespace App\Onlineshop\Facades;

use App\Onlineshop\Client;
use Illuminate\Support\Facades\Facade;

class Onlineshop extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
