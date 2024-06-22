<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;  // Adicione esta linha

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
