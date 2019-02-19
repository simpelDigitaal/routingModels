<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('routingmodels.table', 'routes'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('method')->default('GET');
            $table->string('slug');
            $table->morphs('subject');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('routingmodels.table', 'routes'));
    }
}
