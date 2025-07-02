<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\GuestBuyer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        if ($request->user() && $request->user()->role === 'user') {
            $orders = Order::with(['product', 'buyer', 'guestBuyer'])
                ->whereHas('product', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })
                ->get();
        } else if ($request->user()) {
            $orders = Order::with(['product', 'buyer', 'guestBuyer'])
                ->where('buyer_id', $request->user()->id)
                ->get();
        } else {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            // Informations de l'acheteur invité
            'buyer_info.full_name' => 'required|string|max:255',
            'buyer_info.email' => 'required|email|max:255',
            'buyer_info.phone' => 'required|string|max:20',
            'buyer_info.address' => 'required|string|max:500',
            'buyer_info.wilaya' => 'required|string|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Stock insuffisant'], 400);
        }

        $totalPrice = $product->price * $request->quantity;

        // Créer ou récupérer l'acheteur invité
        $guestBuyer = GuestBuyer::create([
            'full_name' => $request->buyer_info['full_name'],
            'email' => $request->buyer_info['email'],
            'phone' => $request->buyer_info['phone'],
            'address' => $request->buyer_info['address'],
            'wilaya' => $request->buyer_info['wilaya'],
        ]);

        $order = Order::create([
            'product_id' => $request->product_id,
            'buyer_id' => $request->user() ? $request->user()->id : null,
            'guest_buyer_id' => $guestBuyer->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'en_attente',
        ]);

        $product->decrement('stock', $request->quantity);

        return response()->json([
            'order' => $order->load(['product', 'guestBuyer']),
            'message' => 'Commande passée avec succès!'
        ], 201);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:en_attente,confirme,livre,annule',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json($order);
    }

    // Nouvelle méthode pour récupérer une commande par ID (pour les invités)
    public function show($orderId)
    {
        $order = Order::with(['product', 'guestBuyer'])
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }

        return response()->json($order);
    }
}
