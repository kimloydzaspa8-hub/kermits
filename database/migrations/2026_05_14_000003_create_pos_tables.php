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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->default('pcs');
            $table->integer('stock')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('category')->default('foods');
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedInteger('queue_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('source')->default('cashier');
            $table->string('status')->default('received');
            $table->string('payment_status')->default('paid');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('cashiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('cashiers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menu_item_ingredients');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('inventory_items');

        Schema::enableForeignKeyConstraints();
    }
};
