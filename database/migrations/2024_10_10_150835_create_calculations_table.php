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
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->string('bank_name');
            $table->integer('on_duration');
            $table->decimal('gross_interest', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('rate', 5, 2);
            $table->decimal('amount', 10, 2);
            $table->decimal('net_interest', 10, 2);
            $table->decimal('final_balance', 10, 2);
            $table->string('currency');
            $table->integer('duration');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
