<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->enum('status', [
                'draft', 'pending', 'in_progress', 'qc_check', 'rework', 'completed', 'cancelled'
            ])->default('draft')->change();
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->enum('status', [
                'draft', 'pending', 'in_progress', 'qc_check', 'completed', 'cancelled'
            ])->default('draft')->change();
        });
    }
};
