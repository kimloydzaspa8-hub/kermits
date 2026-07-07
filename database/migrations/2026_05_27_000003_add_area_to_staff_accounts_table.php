<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_accounts', function (Blueprint $table) {
            if (! Schema::hasColumn('staff_accounts', 'area')) {
                $table->string('area')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('staff_accounts', 'area')) {
                $table->dropColumn('area');
            }
        });
    }
};
