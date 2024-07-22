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
        Schema::create('procurement_project_management_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('title');
            $table->string('pmo_end_user_dept');
            $table->string('source_of_funds');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->json('attachments')->nullable();
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
        Schema::dropIfExists('procurement_project_management_plans');
    }
};
