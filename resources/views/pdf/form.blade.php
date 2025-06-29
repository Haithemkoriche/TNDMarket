<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form PDF</title>
  <style>
    body {
      font-family: sans-serif;
    }
    h1 {
      color: #333;
    }
  </style>
</head>
<body>
  <h1>Formulaire #{{ $form->id }}</h1>
  <p>Patient : {{ $form->patient_name }} {{ $form->patient_surname }}</p>
  <p>Type de formulaire : {{ $form->formType->name ?? 'Inconnu' }}</p>
  <p>Date : {{ $form->created_at }}</p>

  <h3>Données du formulaire :</h3>
  <ul>
    @foreach ($form->form_data ?? [] as $key => $value)
      <li><strong>{{ $key }}:</strong> {{ $value }}</li>
    @endforeach
  </ul>
</body>
</html>
