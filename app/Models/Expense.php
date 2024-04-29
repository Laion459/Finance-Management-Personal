<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    // Nome da tabela no banco de dados
    protected $table = 'expenses';

    // Campos que podem ser preenchidos em massa
    protected $fillable = ['date', 'amount', 'category_id'];

    // Relacionamento com a tabela de categorias
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
