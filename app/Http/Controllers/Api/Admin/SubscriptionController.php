<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Centre;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('centre')->get();
        return response()->json($subscriptions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'centre_id' => 'required|exists:centres,id',
            'status' => 'required|in:active,suspended,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0'
        ]);

        $subscription = Subscription::create($request->all());

        return response()->json($subscription, 201);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'status' => 'in:active,suspended,cancelled',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'price' => 'numeric|min:0'
        ]);

        $subscription->update($request->all());

        return response()->json($subscription);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response()->json(null, 204);
    }
}
