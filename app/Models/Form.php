<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'form_type_id',
        'patient_name',
        'patient_surname',
        'form_data',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function formType()
    {
        return $this->belongsTo(FormType::class);
    }
}
