<?php

namespace Modules\Order\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Managers\CartManager;
use Modules\Order\Managers\OrderManager;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // product from selected cart
        $cart = Cart::where('user_id', $request->user_id)
                    ->whereIn('id', $request->carts)
                    ->whereNotIn('status', [Cart::STATUS_ORDERED])
                    ->get();

        // flag if there any change of product in cart
        $has_change = CartManager::validate($cart);

        // reload cart model if cart data has change
        if($has_change) {
            $cart = Cart::where('user_id', $request->user_id)
                        ->whereIn('id', $request->carts)
                        ->whereNotIn('status', [Cart::STATUS_ORDERED])
                        ->get();
        }

        // validate order
        $validate = OrderManager::validate($cart);

        if($validate) {
            // db begin transaction, to prevent failure on process
            DB::beginTransaction();
            try {
                // store to order
                $order = new Order;
                $order->user_id = $request->user_id;
                $order->status = Order::STATUS_WAITING_PAYMENT;
                if($order->save()) {
                    // update carts.status on cart to ordered
                    foreach($cart as $value) {
                        // decrease quantity on product.quantity
                        $update_product = Product::find($value->product_id);

                        $update_product->quantity = $update_product->quantity - $value->quantity;
                        $update_product->save();

                        // change cart.status to ordered
                        $update_cart = Cart::find($value->id);

                        $update_cart->status = Cart::STATUS_ORDERED;
                        $update_cart->save();
                    }
                }
                DB::commit();
            } catch(Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'    => 400,
                    'message'   => 'Failed to update DB'
                ]);
            }
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
