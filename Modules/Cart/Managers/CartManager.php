<?php

namespace Modules\Cart\Managers;

use App\Models\Cart;
use App\Models\Product;

class CartManager 
{
    public static function validate($cart)
    {
        // by default return false if there's no adjustment in cart
        $has_change = false;

        // check and update status of current product in cart when meet some condition (out of stock, re-stock)
        foreach($cart as $value) {
            $product = Product::find($value->product_id);
            // check if stock decreasing
            if($product->quantity < $value->quantity) {
                $update_cart = Cart::find($value->id);
                $update_cart->status = Cart::STATUS_OUT_OF_STOCK;
                $update_cart->save();
                $has_change = true;
            }
            // check if product restock
            else {
                $update_cart = Cart::find($value->id);
                if($update_cart->status != Cart::STATUS_READY) {
                    $update_cart->status = Cart::STATUS_READY;
                    $update_cart->save();
                    $has_change = true;
                }
            }
        }

        return $has_change;
    }
}