<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->unsignedTinyInteger('priority_level')->default(50)->after('status');
            $table->unsignedInteger('estimated_duration')->nullable()->after('priority_level');
            $table->boolean('algorithm_generated')->default(false)->after('estimated_duration');
            $table->dateTime('scheduled_start')->nullable()->after('algorithm_generated');
            $table->dateTime('scheduled_end')->nullable()->after('scheduled_start');
            $table->text('schedule_notes')->nullable()->after('scheduled_end');
            $table->json('fitness_data')->nullable()->after('schedule_notes');
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn([
                'priority_level',
                'estimated_duration',
                'algorithm_generated',
                'scheduled_start',
                'scheduled_end',
                'schedule_notes',
                'fitness_data',
            ]);
        });
    }
};
