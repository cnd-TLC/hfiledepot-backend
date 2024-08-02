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
        Schema::create('ppmp_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('category');
            $table->string('general_desc');
            $table->string('unit')->nullable();
            $table->integer('quantity')->nullable();
            $table->boolean('lumpsum')->default(false);
            $table->enum('mode_of_procurement', ['Bidding', 'Shopping']);
            $table->decimal('estimated_budget', 9, 2);
            $table->integer('jan')->nullable();
            $table->integer('feb')->nullable();
            $table->integer('mar')->nullable();
            $table->integer('apr')->nullable();
            $table->integer('may')->nullable();
            $table->integer('jun')->nullable();
            $table->integer('jul')->nullable();
            $table->integer('aug')->nullable();
            $table->integer('sept')->nullable();
            $table->integer('oct')->nullable();
            $table->integer('nov')->nullable();
            $table->integer('dec')->nullable();
            $table->unsignedBigInteger('ppmp_id');
            $table->foreign('ppmp_id')
                    ->references('id')
                    ->on('procurement_project_management_plans')
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
        Schema::dropIfExists('ppmp_items');
    }
};
