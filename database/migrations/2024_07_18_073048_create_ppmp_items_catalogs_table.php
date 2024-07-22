<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppmp_items_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('general_desc');
            $table->string('unit')->nullable();
            // $table->enum('mode_of_procurement', ['Bidding', 'Shopping']);
            $table->integer('year');
            $table->string('department');
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
        Schema::dropIfExists('ppmp_items_catalog');
    }
};
