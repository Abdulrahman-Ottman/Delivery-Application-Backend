<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = $request->user();

        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        //check each item quantity
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return response()->json([
                    'message' => 'Some items in your cart are out of stock.',
                    'product' => $item->product,
                ], 400);
            }
        }

        DB::beginTransaction();

        try {

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            //move the product to the order
            foreach ($cartItems as $item) {
                $order->products()->attach($item->product->id, [
                    'ordered_quantity' => $item->quantity,
                ]);

                //decrment the product stock quantity
                $item->product->decrement('quantity', $item->quantity);
            }

            $user->cartItems()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order->load('products'),
            ], 201);
        } catch (\Exception $e) {
            //undo the changes if any exception accures
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to place the order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUserOrders(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders()->with('products')->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found.'], 404);
        }

        return response()->json([
            'message' => 'Orders retrieved successfully.',
            'orders' => $orders,
        ], 200);
    }

}
