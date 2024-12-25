<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $outOfStockItems = [];

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                $outOfStockItems[] = [
                    'product' => $item->product,
                    'requested_quantity' => $item->quantity,
                    'available_quantity' => $item->product->quantity,
                ];
            }
        }

        if (!empty($outOfStockItems)) {
            return response()->json([
                'message' => 'Some items in your cart are out of stock.',
                'products' => $outOfStockItems,
            ], 400);
        }



        try {
            DB::beginTransaction();

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
        } catch (\Throwable $e) {
            //undo the changes if any exception accures
            try {
                DB::rollBack();
            } catch (\Throwable $e) {

            }

            return response()->json([
                'message' => 'Failed to place the order.',
                'error' => $e->getMessage(),
            ], 500);
        }     }

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
    public function editOrder(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'order_id' => ['required', 'exists:orders,id'],
            'add_products' => ['array'],
            'add_products.*.product_id' => ['required_with:add_products', 'exists:products,id'],
            'add_products.*.quantity' => ['required_with:add_products', 'integer', 'min:1'],
            'remove_products' => ['array'],
            'remove_products.*' => ['required_with:remove_products', 'exists:products,id'],
            'change_quantity' => ['array'],
            'change_quantity.*.product_id' => ['required_with:change_quantity', 'exists:products,id'],
            'change_quantity.*.quantity' => ['required_with:change_quantity', 'integer', 'min:1'],
        ]);

        if (!$request->has('add_products')&&!$request->has('change_quantity')&&!$request->has('remove_products')) {
            return response()->json([
                'message' => "No thing to edit.",
            ], 400);
        }

        if ($validator->fails()){
            return response()->json([
                'message' => "Edit failed",
                'data' =>$validator->errors()
            ], 401);
        }

        $user = $request->user();

        $order = $user->orders()->with('products')->find($request->order_id);

        if (!$order) {
            return response()->json([
                'message' => "Order id is not for this user.",
            ], 404);
        }

        if ($order->status != 'pending') {
            return response()->json([
                'message' => "This order cannot be edited because it is not pending.",
            ], 403);
        }

        $productsInOrder = $order->products->keyBy('id');
        $productsInDB = Product::all()->keyBy('id');

        if ($request->has('add_products')) {
            $outOfStock=[];
            $inTheOrder=[];
            foreach ($request->add_products as $productToAdd) {
                $product = $productsInDB->get($productToAdd['product_id']);
                if($productToAdd['quantity'] > $product->quantity)
                    $outOfStock = $product;
                if($productsInOrder->has($productToAdd['product_id']))
                    $inTheOrder = $product;

            }
            if(!empty($inTheOrder))
                return response()->json([
                    'message' => "Some products is already in the order.",
                    'In the order' => $inTheOrder
                ], 409);
            if(!empty($outOfStock))
                return response()->json([
                    'message' => "Some quantities are out of Stock.",
                    'Out of Stock' => $outOfStock
                ], 400);

            foreach ($request->add_products as $product) {
                    $order->products()->attach($product['product_id'], [
                        'ordered_quantity' => $product['quantity']
                    ]);
                    $productInDB = $productsInDB->get($product['product_id']);
                    $productInDB->decrement('quantity', $product['quantity']);
            }
        }

        if ($request->has('remove_products')) {
            foreach ($request->remove_products as $product_id) {
                $orderedProduct = $productsInOrder->get($product_id);
                if ($orderedProduct)
                    Product::find($product_id)->increment('quantity', $orderedProduct->pivot->ordered_quantity);
            }
            $order->products()->detach($request->remove_products);
        }

        if ($request->has('change_quantity')) {
            $outOfStock=[];
            $notInOrder=[];
            foreach ($request->change_quantity as $product) {
                $existingProductInOrder = $productsInOrder->get($product['product_id']);
                if(!$existingProductInOrder){
                    $notInOrder = $product;
                    continue;
                }
                $existingProductInDB = $productsInDB->get($product['product_id']);
                $newQuantity = $product['quantity']- $existingProductInOrder->pivot->ordered_quantity;
                if ($newQuantity > $existingProductInDB->quantity)
                    $outOfStock = $product;
            }
            if(!empty($notInOrder))
                return response()->json([
                    'message' => "Some products are not in the order.",
                    'Not in the order' => $notInOrder
                ], 404);
            if(!empty($outOfStock))
                return response()->json([
                    'message' => "Some quantities are out of Stock",
                    'Out of Stock' => $outOfStock
                ], 400);

            foreach ($request->change_quantity as $product) {
                $existingProductInOrder = $productsInOrder->get($product['product_id']);
                $existingProductInDB = $productsInDB->get($product['product_id']);
                $newQuantity = $product['quantity']- $existingProductInOrder->pivot->ordered_quantity;
                $existingProductInDB->update(['quantity' => $existingProductInDB->quantity - $newQuantity]);
                $order->products()->updateExistingPivot($product['product_id'], [
                    'ordered_quantity' => $product['quantity']
                ]);
            }
        }
        return response()->json([
            'message' => "Order updated successfully.",
            'order' => $order->load('products')
        ],200);
    }
    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'order_id' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "Delete failed",
                'data' =>$validator->errors()
            ], 401);
        }

        $user = $request->user();

        $order = $user->orders()->with('products')->find($request->order_id);

        if (!$order) {
            return response()->json([
                'message' => "Order id is not for this user.",
            ], 404);
        }

        if ($order->status != 'pending') {
            return response()->json([
                'message' => "This order cannot be canceled because it is not pending.",
            ], 403);
        }

        foreach ($order->products as $product) {
            Product::find($product->id)->increment('quantity', $product->pivot->ordered_quantity);
        }
        $order->products()->detach($order->products->pluck('id'));
        $order->delete();

        return response()->json([
            'message' => "Order canceled successfully.",
        ], 200);
    }
}
