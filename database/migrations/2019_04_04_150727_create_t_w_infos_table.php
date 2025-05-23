<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTWInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_w_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            
            $table->boolean('is_visible')->default(1)->nullable();
            $table->unsignedBigInteger('t_w_id')->index();//->nullable()
            $table->text('description')->nullable();
            //$table->unsignedBigInteger('created_user')->index()->nullable();
            $table->string('created_user')->index();//->nullable()
            
            $table->foreign('t_w_id')->references('id')->on('t_w_s')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_w_infos');
    }
}
