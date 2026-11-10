<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('violation_category_id')->constrained('violation_categories')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->comment('Guru pelapor');
            $table->foreignId('validated_by')->nullable()->constrained('users')->comment('Guru BK validator');
            $table->date('violation_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_violations');
    }
};
