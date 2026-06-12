<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_section_id')->constrained('topic_sections')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'topic_section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_views');
    }
};
