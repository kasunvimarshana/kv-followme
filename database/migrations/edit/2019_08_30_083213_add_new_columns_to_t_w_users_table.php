<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToTWUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_w_users', function (Blueprint $table) {
            //
            $table->boolean('is_done')->default(0)->nullable();
            $table->boolean('is_cloned')->default(0)->nullable();
            $table->boolean('is_archived')->default(0)->nullable();
            $table->boolean('is_reviewable')->default(0)->nullable();
            $table->boolean('is_remindable')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_w_users', function (Blueprint $table) {
            //
            //$table->dropColumn('col');
            $table->dropColumn(['is_done', 'is_cloned', 'is_archived', 'is_reviewable', 'is_remindable']);
        });
    }
}
