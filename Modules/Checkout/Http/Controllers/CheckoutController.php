<?php

namespace Modules\Checkout\Http\Controllers;

use App\Models\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cart\Managers\CartManager;

class CheckoutController extends Controller
{
    public function index(Request $request) 
    {
        // product from selected cart
        $cart = Cart::where('user_id', $request->user_id)
                    ->whereIn('id', $request->carts)
                    ->get();
                    
        // flag for change of product in cart
        $has_validate = CartManager::validate($cart);

        // reload cart model
        if($has_validate) {
            $cart = Cart::where('user_id', $request->user_id)
                        ->whereNotIn('status', [Cart::STATUS_ORDERED])
                        ->get();
        }

        // refresh cart data if there's and adjustment from Cart Manager
        if($has_validate) {
            $cart = Cart::where('user_id', $request->user_id)
                        ->whereIn('product_id', $request->products)
                        ->get();
        }

        return response()->json($cart);
    }
}
