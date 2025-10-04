<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organisateur extends Model
{
    use HasFactory;

    protected $table = 'organisateurs';

    protected $fillable = [
        'utilisateur_id', 'contact', 'adresse'
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
