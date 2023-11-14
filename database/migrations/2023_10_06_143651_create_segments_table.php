<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_segments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_schedule_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedInteger('index_number');
            $table->unsignedBigInteger('principal_payment_in_cents');
            $table->unsignedBigInteger('interest_payment_in_cents');
            $table->unsignedBigInteger('euribor_payment_in_cents');
            $table->unsignedBigInteger('total_payment_in_cents');
            $table->unsignedBigInteger('remaining_principal_in_cents');
            $table->timestamps();

            $table->unique(['loan_schedule_id', 'index_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_segments');
    }
};
