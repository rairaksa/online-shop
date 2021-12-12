<?php

namespace Modules\Product\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return response()->json($products);
    }

    public function detail(Request $request)
    {
        $product = Product::find($request->id);

        if(!$product) {
            // return response 404
        }

        return response()->json($product);
    }

    public function add_to_cart(Request $request)
    {
        $product = Product::find($request->product_id);

        if(!$product) {
            abort(404);
        }

        // check current product is ready on user cart
        $has_on_cart = Cart::where('product_id', $request->product_id)
                            ->where('user_id', $request->user_id)
                            ->whereIn('status', [Cart::STATUS_READY, Cart::STATUS_OUT_OF_STOCK])
                            ->first();

        // validate stock from product.quantity + from cart
        $quantity_on_cart = $has_on_cart->quantity ?? 0;

        // give response if stock 
        if($product->quantity < $request->quantity + $quantity_on_cart) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Out of Stock'
            ]);
        }

        // update quantity in cart if product_id with status = 'READY', 'OUT OF STOCK' is exist
        if($has_on_cart) {
            $cart = $has_on_cart;
            $cart->quantity += + $request->quantity;
            if($cart->save()) {
                return response()->json([
                    'status'    => 200,
                    'message'   => 'Added to Cart'
                ]);
            }
            else {
                return response()->json([
                    'status'    => 400,
                    'message'   => 'Failed Add to Cart'
                ]);
            }
        }

        // insert as row in cart if product_id with status = 'ORDERED', 'OUT OF STOCK' is not exist
        $cart = new Cart;

        $cart->user_id = $request->user_id;
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;
        $cart->status = Cart::STATUS_READY;
        if($cart->save()) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Added to Cart'
            ]);
        }
        else {
            return response()->json([
                'status'    => 400,
                'message'   => 'Failed Add to Cart'
            ]);
        }
    }
}
