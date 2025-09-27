<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'montant', 'mode', 'statut', 'date', 'id_inscription'
    ];

    protected $casts = [
        'date' => 'datetime',
        'montant' => 'decimal:2',
    ];
}
