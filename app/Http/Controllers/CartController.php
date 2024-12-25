<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "Failed to add to cart",
                'data' =>$validator->errors()
            ],401);
        }
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = Product::find($product_id);

        if ($quantity > $product->quantity) {
            return response()->json([
                'message' => 'Not enough stock available.',
            ], 422);
        }

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            if (($cartItem->quantity + $quantity) > $product->quantity) {
                return response()->json([
                    'message' => 'Not enough stock available to update the quantity.',
                ], 422);
            }

            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully.',
        ], 201);
    }

    public function getUserCart(Request $request)
    {
        $user = $request->user();

        $cartItems = $user->cartItems()->with('product')->get();

        return response()->json([
            'cart_items' => $cartItems
        ]);
    }
}
