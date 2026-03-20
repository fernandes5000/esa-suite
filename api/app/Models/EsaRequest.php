<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsaRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $casts = [
        'problem_checkboxes' => 'array',
        'terms_accepted_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'esa_request_pet', 'esa_request_id', 'pet_id');
    }
}