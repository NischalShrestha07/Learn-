<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('course');
            $table->decimal('credits', 4, 1)->default(3);
            $table->decimal('score', 5, 2)->nullable();
            $table->string('letter_grade', 2)->nullable();
            $table->decimal('grade_points', 4, 2)->nullable();
            $table->string('semester');
            $table->year('year');
            $table->boolean('is_elective')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
