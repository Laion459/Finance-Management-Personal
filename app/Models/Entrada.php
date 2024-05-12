<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',
        'subtipo',
        'descricao',
        'valor',
    ];

    // Relação com o modelo User (um para muitos)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
