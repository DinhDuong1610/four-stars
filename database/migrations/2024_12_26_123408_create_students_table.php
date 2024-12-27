<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('msv')->primary();
            $table->string('last_name');
            $table->string('first_name');
            $table->date('birth');
            $table->string('email')->unique();
            $table->string('sc_class');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('note')->nullable();
            $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
