<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $table = 'evenements';

    protected $fillable = [
        'titre',
        'shortDescription',
        'description',
        'date',
        'lieu',
        'category',
        'type',       // free ou paid
        'tarif',      // prix si paid
        'places_max', // nombre maximal de participants
        'inscrits_count', // nombre actuel de participants
        'statut',     // published, draft, etc.
        'image_url',  // URL de l'image
        'organisateur_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'tarif' => 'decimal:2',
        'places_max' => 'integer',
        'inscrits_count' => 'integer',
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participer', 'evenement_id', 'user_id');
    }


}
