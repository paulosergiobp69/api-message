<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // IDMENSAGEM;DDD;CELULAR;OPERADORA;HORARIO_ENVIO;MENSAGEM        
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('idmessage',36)->nullable()->comment('Identificador da Mensagem');
            $table->string('ddd',3)->nullable()->comment('DDD do telefone do cliente');
            $table->string('cellphone',9)->nullable()->comment('Fone do cliente');
            $table->string('operator',20)->nullable()->comment('Operadora do cliente');
            $table->string('time_shipping',8)->nullable()->comment('Horario de envio da mensagem');
            $table->text('message')->nullable()->comment('Texto da Mensagem');
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
        Schema::dropIfExists('messages');
    }
}
