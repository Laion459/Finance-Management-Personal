<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Nome da tabela no banco de dados
    protected $table = 'categories';

    // Campos que podem ser preenchidos em massa
    protected $fillable = ['type', 'subtype', 'category_type']; 

    // Relacionamento com despesas
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
