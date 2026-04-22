<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('pic_name');
            $table->foreignId('rework_of')->nullable()->constrained('productions')->onDelete('set null')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['rework_of']);
            $table->dropColumn(['user_id', 'rework_of']);
        });
    }
};