<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $forms = Form::with(['formType', 'centre'])
            ->where('centre_id', $request->user()->centre->id)
            ->get();

        return response()->json($forms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_type_id' => 'required|exists:form_types,id',
            'patient_name' => 'required|string',
            'patient_surname' => 'required|string',
            'form_data' => 'required|array',
        ]);

        $form = Form::create([
            'centre_id' => $request->user()->centre->id,
            'form_type_id' => $request->form_type_id,
            'patient_name' => $request->patient_name,
            'patient_surname' => $request->patient_surname,
            'form_data' => $request->form_data,
        ]);

        return response()->json($form->load(['formType', 'centre']), 201);
    }

    public function show(Form $form)
    {
        return response()->json($form->load(['formType', 'centre']));
    }

    public function generatePdf(Form $form)
    {
        $form->load(['formType', 'centre']);

        $pdf = Pdf::loadView('pdf.form', compact('form'));

        return $pdf->download('form_' . $form->id . '.pdf');
    }

    public function getFormTypes()
    {
        $formTypes = FormType::where('is_active', true)->get();
        return response()->json($formTypes);
    }
}
