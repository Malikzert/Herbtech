<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('name');
            $table->string('unit', 50)->nullable()->after('description');
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->string('supplier', 255)->nullable()->after('unit');
            $table->decimal('min_stock_level', 15, 2)->nullable()->after('current_stock');
            $table->string('sku', 100)->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'unit']);
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn(['supplier', 'min_stock_level', 'sku']);
        });
    }
};