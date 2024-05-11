<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',
        'descricao',
        'valor',
    ];


    // Relacionamento com a tabela de categorias
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Relação com o modelo User (um para muitos)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
