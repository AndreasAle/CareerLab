<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('target_position');
            $table->enum('hrd_mode', ['friendly', 'strict', 'corporate', 'startup', 'trap_question', 'galak_mode'])->default('friendly');
            $table->enum('difficulty', ['easy', 'normal', 'hard'])->default('normal');
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->integer('final_score')->nullable();
            $table->text('feedback_summary')->nullable();
            $table->json('report_data')->nullable();
            $table->timestamps();
        });

        Schema::create('interview_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_session_id')->constrained()->cascadeOnDelete();
            $table->enum('sender', ['ai_hrd', 'user']);
            $table->longText('message');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_messages');
        Schema::dropIfExists('interview_sessions');
    }
};
