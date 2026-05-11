<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            // Cek sebelum menambah target_quantity
            if (!Schema::hasColumn('productions', 'target_quantity')) {
                $table->unsignedInteger('target_quantity')->default(100)->after('product_id');
            }
            
            // Mengubah actual_quantity menjadi nullable
            $table->unsignedInteger('actual_quantity')->default(0)->nullable()->change();
            
            // Cek sebelum menambah target_date
            if (!Schema::hasColumn('productions', 'target_date')) {
                $table->dateTime('target_date')->nullable()->after('end_date');
            }
        });

        Schema::table('productions', function (Blueprint $table) {
            // Update status ENUM (Pastikan doctrine/dbal sudah terinstall untuk .change())
            $table->enum('status', ['draft', 'pending', 'in_progress', 'qc_check', 'completed', 'cancelled'])
                  ->default('draft')
                  ->change();
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('raw_materials', 'expired_date')) {
                $table->date('expired_date')->nullable()->after('supplier');
            }
            if (!Schema::hasColumn('raw_materials', 'price_per_unit')) {
                $table->decimal('price_per_unit', 12, 2)->default(0)->after('expired_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn(['target_quantity', 'target_date']);
            // Kembalikan ke status awal jika perlu
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn(['expired_date', 'price_per_unit']);
        });
    }
};