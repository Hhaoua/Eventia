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
        'type',
        'tarif',
        'places_max',
        'inscrits_count',
        'statut',
        'image_url',
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
