<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = 0;
        $order->save();

        $totalPrice = 0;

        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $quantity = $productData['quantity'];
            $price = $product->price;

            $totalPrice += $price * $quantity;

            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

        $order->total_price = $totalPrice;
        $order->save();

        return response()->json($order->load('products'), 201);
    }
}
