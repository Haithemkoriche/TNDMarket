<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use Illuminate\Http\Request;

class CentreController extends Controller
{
    public function index()
    {
        $centres = Centre::with(['user', 'subscription'])->get();
        return response()->json($centres);
    }

    public function update(Request $request, Centre $centre)
    {
        $request->validate([
            'name' => 'string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $centre->update($request->all());

        return response()->json($centre);
    }

    public function destroy(Centre $centre)
    {
        $centre->delete();
        return response()->json(null, 204);
    }
}
