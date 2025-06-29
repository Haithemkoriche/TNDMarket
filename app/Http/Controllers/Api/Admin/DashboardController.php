<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Centre;
use App\Models\Product;
use App\Models\Order;
use App\Models\Form;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_centres' => Centre::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_forms' => Form::count(),
        ];

        return response()->json($stats);
    }

    public function activities()
    {
        $activities = [];

        $recentOrders = Order::with(['product', 'buyer'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'message' => $order->buyer->name . ' a commandé ' . $order->product->title,
                    'created_at' => $order->created_at,
                ];
            });

        $recentForms = Form::with(['centre', 'formType'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($form) {
                return [
                    'type' => 'form',
                    'message' => $form->centre->name . ' a créé un formulaire ' . $form->formType->name,
                    'created_at' => $form->created_at,
                ];
            });

        $recentProducts = Product::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'type' => 'product',
                    'message' => $product->user->name . ' a ajouté le produit ' . $product->title,
                    'created_at' => $product->created_at,
                ];
            });

        $activities = collect($recentOrders)
            ->merge($recentForms)
            ->merge($recentProducts)
            ->sortByDesc('created_at')
            ->take(20)
            ->values();

        return response()->json($activities);
    }
}
