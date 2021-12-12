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
                    ->whereNotIn('status', [Cart::STATUS_ORDERED])
                    ->get();
                    
        // flag if there any change of product in cart
        $has_validate = CartManager::validate($cart);

        // reload cart model if cart data has change
        if($has_validate) {
            $cart = Cart::where('user_id', $request->user_id)
                        ->whereIn('id', $request->carts)
                        ->whereNotIn('status', [Cart::STATUS_ORDERED])
                        ->get();
        }

        if(!$cart) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Empty Checkout',
                'data'      => []
            ]); 
        }
        
        return response()->json([
            'status' => 200,
            'data'   => $cart
        ]);
    }
}
