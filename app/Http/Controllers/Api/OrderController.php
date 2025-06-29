<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        if ($request->user()->role === 'user') {
            $orders = Order::with(['product', 'buyer'])
                ->whereHas('product', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })
                ->get();
        } else {
            $orders = Order::with(['product', 'buyer'])
                ->where('buyer_id', $request->user()->id)
                ->get();
        }

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Stock insuffisant'], 400);
        }

        $totalPrice = $product->price * $request->quantity;

        $order = Order::create([
            'product_id' => $request->product_id,
            'buyer_id' => $request->user()->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
        ]);

        $product->decrement('stock', $request->quantity);

        return response()->json($order->load(['product', 'buyer']), 201);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:en_attente,confirme,livre',
        ]);

        $this->authorize('update', $order);

        $order->update(['status' => $request->status]);

        return response()->json($order);
    }
}
