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
        if (Schema::hasTable('cashier_pos')) {
            return;
        }

        Schema::create('cashier_pos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->string('terminal_name')->default('Cashier POS');
            $table->string('status')->default('active');
            $table->decimal('opening_cash', 10, 2)->default(0);
            $table->decimal('closing_cash', 10, 2)->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashier_pos');
    }
};
