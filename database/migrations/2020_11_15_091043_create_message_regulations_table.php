<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageRegulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_regulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->nullable()->constrained()->onDelete('restrict')->comment('ID da mensagem processada');
            $table->foreignId('regulation_id')->nullable()->constrained()->onDelete('restrict')->comment('ID da regra avaliada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_regulations');
    }
}
