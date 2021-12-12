<?php

namespace Modules\Order\Http\Controllers;

use App\Models\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cart\Managers\CartManager;
use Modules\Order\Managers\OrderManager;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // product from selected cart
        $cart = Cart::where('user_id', $request->user_id)
                    ->whereIn('carts', $request->carts)
                    ->get();

        // flag for change of product in cart
        $has_validate = CartManager::validate($cart);

        // reload cart model
        if($has_validate) {
            $cart = Cart::where('user_id', $request->user_id)
                        ->whereNotIn('status', [Cart::STATUS_ORDERED])
                        ->get();
        }

        // validate order
        $validate = OrderManager::validate($cart);

        if($validate) {
            // decrease quantity on product.quantity
            // store to order
            // generate payment info
            return response()->json([
                'status'    => 200,
                'message'   => 'Your order has been created'
            ]);
        } else {
            // return error there's a product issue
            return response()->json([
                'status'    => 400,
                'message'   => 'Your order has out of stock product'
            ]);
        }
    }
}
