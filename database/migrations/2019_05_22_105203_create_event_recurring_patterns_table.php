<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventRecurringPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_recurring_patterns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            
            $table->boolean('is_visible')->default(1)->nullable();
            $table->morphs('recurrable');
            $table->boolean('is_recurring')->default(0)->nullable();
            $table->unsignedBigInteger('recurring_type_id')->index()->nullable();
            $table->unsignedInteger('minute')->default(0)->nullable()->comment('1 - 60 (0:ignore)');
            $table->unsignedInteger('hour')->default(0)->nullable()->comment('1 - 24 (0:ignore)');
            $table->unsignedInteger('day')->default(0)->nullable()->comment('day (0:ignore)');
            $table->unsignedInteger('day_of_month')->default(0)->nullable()->comment('1 - 31 (0:ignore)');
            $table->unsignedInteger('month')->default(0)->nullable()->comment('1 - 12 (0:ignore)');
            $table->unsignedInteger('day_of_week')->default(0)->nullable()->comment('1 - 7 (sunday = 7) (0:ignore)');
            $table->unsignedInteger('year')->default(0)->nullable()->comment('year (0:ignore)');
            $table->boolean('has_max_number_of_occures')->default(0)->nullable();
            $table->unsignedBigInteger('max_number_of_occures')->default(0)->nullable();
            $table->boolean('has_seperation_count')->default(0)->nullable();
            $table->unsignedBigInteger('seperation_count')->default(0)->nullable();
            $table->dateTime('last_event_at')->default(null)->nullable();
            $table->dateTime('next_event_at')->default(null)->nullable();
            $table->unsignedBigInteger('number_of_occures')->default(0)->nullable();
            
            $table->foreign('recurring_type_id')->references('id')->on('recurring_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_recurring_patterns');
    }
}
