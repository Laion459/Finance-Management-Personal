<?php

// database/migrations/[timestamp]_create_saidas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaidasTable extends Migration
{
    public function up()
    {
        Schema::create('saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo_despesa');
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->string('categoria');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saidas');
    }
}


