<?php

namespace Modules\Cart\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cart\Managers\CartManager;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user_id)
                    ->whereNotIn('status', [Cart::STATUS_ORDERED])
                    ->get();

        // flag for change of product in cart
        $has_change = CartManager::validate($cart);

        // reload cart model if cart data has change
        if($has_change) {
            $cart = Cart::where('user_id', $request->user_id)
                        ->whereNotIn('status', [Cart::STATUS_ORDERED])
                        ->get();
        }

        return response()->json($cart);
    }
}
