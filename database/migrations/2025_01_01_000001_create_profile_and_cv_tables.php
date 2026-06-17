<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('education')->nullable();
            $table->string('major')->nullable();
            $table->string('graduation_year')->nullable();
            $table->enum('current_status', ['student', 'fresh_graduate', 'unemployed', 'employed', 'career_switcher'])->nullable();
            $table->string('target_industry')->nullable();
            $table->string('target_role')->nullable();
            $table->text('work_experience_summary')->nullable();
            $table->json('skills')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->timestamps();
        });

        Schema::create('cv_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('original_filename');
            $table->string('file_path');
            $table->longText('extracted_text')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('mime_type')->nullable();
            $table->enum('status', ['uploaded', 'processing', 'completed', 'failed'])->default('uploaded');
            $table->timestamps();
        });

        Schema::create('cv_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cv_upload_id')->constrained()->cascadeOnDelete();
            $table->string('target_position')->nullable();
            $table->integer('score')->default(0);
            $table->integer('ats_score')->default(0);
            $table->text('hrd_first_impression')->nullable();
            $table->json('strengths')->nullable();
            $table->json('weaknesses')->nullable();
            $table->json('red_flags')->nullable();
            $table->json('improvement_suggestions')->nullable();
            $table->text('rewritten_summary')->nullable();
            $table->json('missing_keywords')->nullable();
            $table->longText('ai_raw_response')->nullable();
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_reviews');
        Schema::dropIfExists('cv_uploads');
        Schema::dropIfExists('user_profiles');
    }
};
