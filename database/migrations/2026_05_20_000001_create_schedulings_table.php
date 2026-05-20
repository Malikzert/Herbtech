<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedulings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('batch_number_recommendation', 100)->nullable();
            $table->integer('recommended_quantity')->default(1);
            $table->integer('priority_order')->nullable();
            $table->boolean('is_recommended')->default(true);
            $table->string('critical_material_name')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->enum('status', ['draft', 'approved', 'converted_to_production'])->default('draft');
            $table->timestamps();

            $table->index('product_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedulings');
    }
};
