<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cv_review_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('job_match_check_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('red_flag_scan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->integer('overall_score')->default(0);
            $table->json('report_data')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->timestamps();
        });

        Schema::create('application_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('position');
            $table->string('job_source')->nullable();
            $table->date('applied_at')->nullable();
            $table->enum('status', ['saved', 'applied', 'screening', 'interview', 'test', 'offering', 'accepted', 'rejected', 'ghosted'])->default('saved');
            $table->text('notes')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_trackers');
        Schema::dropIfExists('career_reports');
    }
};
