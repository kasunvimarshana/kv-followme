<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToTwsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_w_s', function (Blueprint $table) {
            //
            $table->boolean('is_cloned_child')->default(0)->nullable();
            $table->unsignedBigInteger('cloned_parent_id')->index()->nullable();
            $table->boolean('is_archived')->default(0)->nullable();
            $table->boolean('is_reviewable')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_w_s', function (Blueprint $table) {
            //
            //$table->dropColumn('col');
            $table->dropColumn(['is_cloned_child', 'cloned_parent_id', 'is_archived', 'is_reviewable']);
        });
    }
}
