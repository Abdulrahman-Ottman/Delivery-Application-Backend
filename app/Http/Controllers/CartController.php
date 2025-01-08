<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $cartItems = $user->cartItems()->with([
            'product' => function ($query) {
                $query->with([
                    'mainImage:id,product_id,path',
                    'store:id,name,location,discount'
                ]);
            }
        ])->get();

        return response()->json([
            'cart_items' => $cartItems
        ]);
    }

    public function removeItem(Request $request){

        $validator = Validator::make($request->all() , [
           'id'=> ['required','exists:carts,id']
       ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "failed to remove the item",
                'data' =>$validator->errors()
            ],400);
        }

        Auth::user()->cartItems()->find($request->id)->delete();

        return \response()->json([
            'message'=>"item removed successfully",
        ],200);
    }

    public function editCartItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:carts,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Failed to update cart item',
                'data' => $validator->errors(),
            ], 400);
        }

        $id = $request->input('id');
        $quantity = $request->input('quantity');

        $cartItem = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        $product = Product::find($cartItem->product_id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        if ($quantity > $product->quantity) {
            return response()->json([
                'message' => 'Not enough stock available.',
            ], 422);
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item updated successfully.',
            'data' => $cartItem,
        ], 200);
    }

}
