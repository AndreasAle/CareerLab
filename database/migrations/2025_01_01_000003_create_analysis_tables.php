<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('red_flag_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cv_upload_id')->nullable()->constrained()->nullOnDelete();
            $table->string('target_position')->nullable();
            $table->integer('score')->default(0);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->json('candidate_red_flags')->nullable();
            $table->json('explanation')->nullable();
            $table->json('safe_fix_suggestions')->nullable();
            $table->longText('ai_raw_response')->nullable();
            $table->timestamps();
        });

        Schema::create('job_match_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cv_upload_id')->nullable()->constrained()->nullOnDelete();
            $table->string('job_title');
            $table->string('company_name')->nullable();
            $table->longText('job_description');
            $table->integer('match_score')->default(0);
            $table->json('matched_skills')->nullable();
            $table->json('missing_skills')->nullable();
            $table->json('required_keywords')->nullable();
            $table->json('recommended_cv_changes')->nullable();
            $table->text('suggested_cv_summary')->nullable();
            $table->enum('should_apply', ['yes', 'maybe', 'no'])->default('maybe');
            $table->longText('ai_raw_response')->nullable();
            $table->timestamps();
        });

        Schema::create('toxic_job_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->longText('job_description');
            $table->integer('toxicity_score')->default(0);
            $table->json('warning_signs')->nullable();
            $table->json('questions_to_ask_hr')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->longText('ai_raw_response')->nullable();
            $table->timestamps();
        });

        Schema::create('salary_simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('target_position');
            $table->string('city')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('offered_salary')->nullable();
            $table->enum('scenario', ['first_offer', 'lowball_offer', 'negotiation', 'final_offer'])->default('first_offer');
            $table->integer('score')->nullable();
            $table->longText('ai_feedback')->nullable();
            $table->longText('suggested_answer')->nullable();
            $table->json('report_data')->nullable();
            $table->timestamps();
        });

        Schema::create('rejection_autopsies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('rejection_type', ['no_response', 'failed_hr_interview', 'failed_user_interview', 'failed_test', 'ghosted', 'offering_failed']);
            $table->longText('story');
            $table->string('target_position')->nullable();
            $table->json('possible_causes')->nullable();
            $table->json('improvement_plan')->nullable();
            $table->json('next_action')->nullable();
            $table->longText('ai_raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rejection_autopsies');
        Schema::dropIfExists('salary_simulations');
        Schema::dropIfExists('toxic_job_scans');
        Schema::dropIfExists('job_match_checks');
        Schema::dropIfExists('red_flag_scans');
    }
};
