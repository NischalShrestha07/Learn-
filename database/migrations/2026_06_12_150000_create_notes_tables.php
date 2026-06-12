<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color', 7)->nullable();
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('note_note_tag', function (Blueprint $table) {
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('note_tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['note_id', 'note_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_note_tag');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('note_tags');
    }
};
