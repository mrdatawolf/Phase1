<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImproveResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('improve_resources', function (Blueprint $table) {
            $table->id();
            $table->integer('resource_id')->unsigned()->index();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->integer('r1')->default(0);
            $table->integer('r2')->default(0);
            $table->integer('r3')->default(0);
            $table->integer('r4')->default(0);
            $table->integer('r5')->default(0);
            $table->integer('r6')->default(0);
            $table->integer('r7')->default(0);
            $table->integer('r8')->default(0);
            $table->integer('r9')->default(0);
            $table->integer('r10')->default(0);
            $table->integer('r11')->default(0);
            $table->integer('r12')->default(0);
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
        Schema::dropIfExists('automate_resources');
    }
}
