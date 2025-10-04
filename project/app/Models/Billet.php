<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evenement_id',
        'code_unique',
        'qr_code_path',
        'statut',
        'date_utilisation',
        'inscription_id'

    ];

    protected $casts = [
        'date_utilisation' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }
}
