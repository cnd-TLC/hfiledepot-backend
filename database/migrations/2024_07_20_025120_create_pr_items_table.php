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
        Schema::create('pr_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_no');
            $table->string('unit')->nullable();
            $table->string('category');
            $table->string('item_description');
            $table->string('quantity')->nullable();
            $table->decimal('unit_cost', 9, 2);
            $table->boolean('lumpsum')->default(false);
            $table->enum('mode_of_procurement', ['Bidding', 'Shopping']);
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('pr_id');
            $table->foreign('pr_id')
                    ->references('id')
                    ->on('purchase_requests')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('pr_items');
    }
};
