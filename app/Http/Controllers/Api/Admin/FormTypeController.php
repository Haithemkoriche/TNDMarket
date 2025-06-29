<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use Illuminate\Http\Request;

class FormTypeController extends Controller
{
    //
      public function index()
    {
        $formTypes = FormType::all();
        return response()->json($formTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $formType = FormType::create([
            'name' => $request->name,
            'description' => $request->description,
            'fields' => $request->fields,
            'is_active' => $request->is_active ?? true
        ]);

        return response()->json($formType, 201);
    }

    public function update(Request $request, FormType $formType)
    {
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'fields' => 'array',
            'is_active' => 'boolean'
        ]);

        $formType->update($request->all());

        return response()->json($formType);
    }

    public function destroy(FormType $formType)
    {
        $formType->delete();
        return response()->json(null, 204);
    }
}
