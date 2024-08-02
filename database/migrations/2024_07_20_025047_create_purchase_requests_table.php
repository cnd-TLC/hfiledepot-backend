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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no')->nullable();
            $table->string('department');
            $table->string('section')->nullable();
            $table->string('requested_by');
            $table->string('cash_availability')->nullable();
            $table->string('fpp')->nullable();
            $table->string('fund')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('purpose');
            $table->string('approved_by_cbo_name')->nullable();
            $table->timestamp('approved_by_cbo', precision: 0)->nullable();
            $table->string('approved_by_cto_name')->nullable();
            $table->timestamp('approved_by_cto', precision: 0)->nullable();
            $table->string('approved_by_cmo_name')->nullable();
            $table->timestamp('approved_by_cmo', precision: 0)->nullable();
            $table->string('approved_by_bac_name')->nullable();
            $table->timestamp('approved_by_bac', precision: 0)->nullable();
            $table->string('approved_by_cgso_name')->nullable();
            $table->timestamp('approved_by_cgso', precision: 0)->nullable();
            $table->string('approved_by_cao_name')->nullable();
            $table->timestamp('approved_by_cao', precision: 0)->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('purchase_requests');
    }
};
