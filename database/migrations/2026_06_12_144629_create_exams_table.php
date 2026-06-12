<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('course')->nullable();
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('location')->nullable();
            $table->string('exam_type')->default('other');
            $table->string('status')->default('upcoming');
            $table->decimal('grade', 5, 2)->nullable();
            $table->decimal('max_grade', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
