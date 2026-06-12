<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('generation_status');
            $table->string('status')->default('active')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('generation_status', ['pending', 'completed', 'failed'])->default('pending')->after('description');
        });
    }
};
