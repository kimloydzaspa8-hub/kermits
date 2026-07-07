<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('order_items');
        Schema::dropIfExists('menu_item_ingredients');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('inventory_items');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
