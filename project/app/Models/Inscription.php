<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = ['user_id', 'evenement_id', 'paid_at', 'checked_at']; // adapte à ta table

    public function evenement()
    {
        return $this->belongsTo(\App\Models\Evenement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billet()
    {
        return $this->hasOne(\App\Models\Billet::class);
    }
}
