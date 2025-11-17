<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsaRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wizard_step',
        'certificate_name',
        'problem_checkboxes',
        'description',
        'terms_accepted_at',
        'status',
        'fee_cents',
    ];

    protected $casts = [
        'problem_checkboxes' => 'array',
        'terms_accepted_at' => 'datetime',
        'fee_cents' => 'integer',
        'wizard_step' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'esa_request_pet');
    }
}