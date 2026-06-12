<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->boolean('completed')->default(true);
            $table->decimal('value', 8, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['habit_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};
