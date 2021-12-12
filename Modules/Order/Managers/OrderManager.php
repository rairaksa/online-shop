<?php

namespace Modules\Order\Managers;

use App\Models\Cart;
use App\Models\Product;

class OrderManager 
{
    public static function validate($cart)
    {
        $validate = true;
        
        foreach($cart as $value) {
            // check if product has not ready
            if($value->status != Cart::STATUS_READY) {
                $validate = false;
            }
        }

        return $validate;
    }
}