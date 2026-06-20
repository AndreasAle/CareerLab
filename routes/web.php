<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AiLogController;
use App\Http\Controllers\Admin\AiPromptController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Coach\CoachDashboardController;
use App\Http\Controllers\Coach\ConsultationController as CoachConsultationController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CvReviewController;
use App\Http\Controllers\CvUploadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ApplicationTrackerController;
use App\Http\Controllers\CareerReportController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\First90DaysController;
use App\Http\Controllers\JobMatchController;
use App\Http\Controllers\RedFlagController;
use App\Http\Controllers\RejectionAutopsyController;
use App\Http\Controllers\SalarySimulatorController;
use App\Http\Controllers\SocialAuditController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ToxicJobController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');

// Public free CV check (no login) — HelloPDF-style instant trial + AI chat.
Route::get('/cek-cv', [\App\Http\Controllers\FreeTrialController::class, 'index'])->name('free.cv');
Route::post('/cek-cv', [\App\Http\Controllers\FreeTrialController::class, 'analyze'])
    ->middleware('throttle:10,1')->name('free.cv.analyze');
Route::post('/cek-cv/chat', [\App\Http\Controllers\FreeTrialController::class, 'chat'])
    ->middleware('throttle:20,1')->name('free.cv.chat');
Route::get('/blog', [LandingController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{post:slug}', [LandingController::class, 'blogShow'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Authenticated (any role) - dashboard router + profile
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| User area (role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    // CV upload + HRD Black Box review
    Route::get('/cv', [CvUploadController::class, 'index'])->name('cv.index');
    Route::post('/cv/upload', [CvUploadController::class, 'store'])->name('cv.upload');
    Route::delete('/cv/{cv}', [CvUploadController::class, 'destroy'])->name('cv.destroy');
    Route::get('/cv/{cv}/review', [CvReviewController::class, 'show'])->name('cv.review.show');
    Route::post('/cv/{cv}/review', [CvReviewController::class, 'run'])
        ->middleware('throttle:20,1')->name('cv.review.run');
    Route::get('/review/{review}', [CvReviewController::class, 'showReview'])->name('cv.review.detail');

    // Interview Drama Simulator
    Route::get('/interview', [InterviewController::class, 'index'])->name('interview.index');
    Route::post('/interview/start', [InterviewController::class, 'start'])
        ->middleware('throttle:20,1')->name('interview.start');
    Route::get('/interview/{session}', [InterviewController::class, 'show'])->name('interview.show');
    Route::post('/interview/{session}/message', [InterviewController::class, 'message'])
        ->middleware('throttle:40,1')->name('interview.message');
    Route::post('/interview/{session}/finish', [InterviewController::class, 'finish'])
        ->middleware('throttle:20,1')->name('interview.finish');

    // Job Match Reality Check
    Route::get('/job-match', [JobMatchController::class, 'index'])->name('job-match.index');
    Route::post('/job-match/check', [JobMatchController::class, 'check'])
        ->middleware('throttle:20,1')->name('job-match.check');
    Route::get('/job-match/{check}', [JobMatchController::class, 'show'])->name('job-match.show');

    // Red Flag Scanner
    Route::get('/red-flag', [RedFlagController::class, 'index'])->name('red-flag.index');
    Route::post('/red-flag/scan', [RedFlagController::class, 'scan'])
        ->middleware('throttle:20,1')->name('red-flag.scan');
    Route::get('/red-flag/{scan}', [RedFlagController::class, 'show'])->name('red-flag.show');

    // Toxic Workplace Detector
    Route::get('/toxic-job', [ToxicJobController::class, 'index'])->name('toxic-job.index');
    Route::post('/toxic-job/scan', [ToxicJobController::class, 'scan'])
        ->middleware('throttle:20,1')->name('toxic-job.scan');
    Route::get('/toxic-job/{scan}', [ToxicJobController::class, 'show'])->name('toxic-job.show');

    // Salary Negotiation Simulator
    Route::get('/salary-simulator', [SalarySimulatorController::class, 'index'])->name('salary.index');
    Route::post('/salary-simulator/start', [SalarySimulatorController::class, 'start'])
        ->middleware('throttle:20,1')->name('salary.start');
    Route::get('/salary-simulator/{simulation}', [SalarySimulatorController::class, 'show'])->name('salary.show');

    // Rejection Autopsy
    Route::get('/rejection-autopsy', [RejectionAutopsyController::class, 'index'])->name('rejection.index');
    Route::post('/rejection-autopsy/analyze', [RejectionAutopsyController::class, 'analyze'])
        ->middleware('throttle:20,1')->name('rejection.analyze');
    Route::get('/rejection-autopsy/{autopsy}', [RejectionAutopsyController::class, 'show'])->name('rejection.show');

    // Social Media HR Check
    Route::get('/social-audit', [SocialAuditController::class, 'index'])->name('social-audit.index');
    Route::post('/social-audit/check', [SocialAuditController::class, 'check'])
        ->middleware('throttle:20,1')->name('social-audit.check');

    // First 90 Days Survival Plan
    Route::get('/first-90-days', [First90DaysController::class, 'index'])->name('first-90-days.index');
    Route::post('/first-90-days/generate', [First90DaysController::class, 'generate'])
        ->middleware('throttle:20,1')->name('first-90-days.generate');

    // Career Diagnosis Report PDF
    Route::get('/career-report', [CareerReportController::class, 'index'])->name('career-report.index');
    Route::post('/career-report/generate', [CareerReportController::class, 'generate'])
        ->middleware('throttle:10,1')->name('career-report.generate');
    Route::get('/career-report/{report}/download', [CareerReportController::class, 'download'])->name('career-report.download');

    // Application Tracker
    Route::get('/applications', [ApplicationTrackerController::class, 'index'])->name('applications.index');
    Route::post('/applications', [ApplicationTrackerController::class, 'store'])->name('applications.store');
    Route::put('/applications/{application}', [ApplicationTrackerController::class, 'update'])->name('applications.update');
    Route::patch('/applications/{application}/status', [ApplicationTrackerController::class, 'updateStatus'])->name('applications.status');
    Route::delete('/applications/{application}', [ApplicationTrackerController::class, 'destroy'])->name('applications.destroy');

    // Template Library
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');

    // Challenge 7 Hari
    Route::get('/challenge', [ChallengeController::class, 'index'])->name('challenge.index');
    Route::post('/challenge/task/{task}/toggle', [ChallengeController::class, 'toggle'])->name('challenge.toggle');

    // Orders (manual payment)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof');

    // Consultation booking
    Route::get('/consultation', [ConsultationController::class, 'index'])->name('consultation.index');
    Route::post('/consultation/book', [ConsultationController::class, 'book'])->name('consultation.book');
});

/*
|--------------------------------------------------------------------------
| Admin area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');

    // Plans
    Route::resource('plans', AdminPlanController::class)->except('show');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('orders.approve');
    Route::patch('/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');

    // Templates
    Route::resource('templates', AdminTemplateController::class)->except('show');

    // AI Prompts
    Route::get('/ai-prompts', [AiPromptController::class, 'index'])->name('ai-prompts.index');
    Route::get('/ai-prompts/{aiPrompt}/edit', [AiPromptController::class, 'edit'])->name('ai-prompts.edit');
    Route::put('/ai-prompts/{aiPrompt}', [AiPromptController::class, 'update'])->name('ai-prompts.update');

    // AI Logs
    Route::get('/ai-logs', [AiLogController::class, 'index'])->name('ai-logs.index');

    // Blog
    Route::resource('blog', AdminBlogController::class)->except('show');

    // Testimonials
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Consultations
    Route::get('/consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
    Route::patch('/consultations/{consultation}/assign', [AdminConsultationController::class, 'assign'])->name('consultations.assign');
});

/*
|--------------------------------------------------------------------------
| Coach area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/', [CoachDashboardController::class, 'index'])->name('dashboard');
    Route::get('/consultations', [CoachConsultationController::class, 'index'])->name('consultations.index');
    Route::patch('/consultations/{consultation}', [CoachConsultationController::class, 'update'])->name('consultations.update');
});

require __DIR__.'/auth.php';
