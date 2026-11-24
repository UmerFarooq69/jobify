<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Job_taskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [\App\Http\Controllers\Jobify\HomeController::class, 'index'])->name('home');
Route::get('index', [Job_taskController::class, 'index'])->name('jobs.index');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes (Requires Authentication & Admin Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/index', [AdminController::class, 'index'])->name('admin.company.companies');

    // Jobs Management
    Route::get('/admin/jobs', [AdminController::class, 'Jobs'])->name('admin.job.jobs');
    Route::delete('/admin/jobs/{job}', [AdminController::class, 'destroyJob'])->name('admin.jobs.destroy');

    // User Management
    Route::patch('/admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    // Company Management
    Route::delete('admin/company/{company}', [AdminController::class, 'destroy'])->name('company.destroy');
    Route::get('/companies/{company}/jobs', [AdminController::class, 'showJobs'])->name('companies.show');

    // Applications Management
    Route::get('/applications', [AdminController::class, 'applications'])->name('applications.index');
    Route::delete('/applications/{application}', [AdminController::class, 'destroyApplication'])->name('applications.destroy');
});

/*
|--------------------------------------------------------------------------
| User Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('Users.dashboard');

    // Jobs Management
    Route::get('/user/jobs', [DashboardController::class, 'Jobs'])->name('User.job.jobs');
    Route::delete('/user/jobs/{job}', [DashboardController::class, 'destroyJob'])->name('User.job.jobs.destroy');

    // Company
    Route::get('/user/company', [DashboardController::class, 'company'])->name('User.company.companies');
    Route::delete('/company/{company}', [DashboardController::class, 'destroy'])->name('user.company.destroy');

    // Applications
    Route::get('/user/applications', [DashboardController::class, 'applications'])->name('User.applications.application');
    Route::delete('/user/applications/{application}', [DashboardController::class, 'destroyApplication'])->name('application.destroy');
});

/*
|--------------------------------------------------------------------------
| Job Routes
|--------------------------------------------------------------------------
*/
Route::get('/job/create', [Job_taskController::class, 'create'])->name('jobs.create');
Route::post('/job', [Job_taskController::class, 'store'])->name('jobs.store');
Route::get('/jobs/{job}/apply', [Job_taskController::class, 'apply'])->name('jobs.apply');
Route::post('/jobs/{job}/apply', [Job_taskController::class, 'submitApplication'])->name('jobs.submitApplication');
Route::get('/jobs/{id}', [SalariesController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{job}/edit', [Job_taskController::class, 'edit'])->name('jobs.edit');
Route::put('/jobs/{job}', [Job_taskController::class, 'update'])->name('jobs.update');

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
*/
Route::get('/company', [CompanyController::class, 'index'])->name('companies');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
Route::get('/company/create', [CompanyController::class, 'create'])->name('companies.create');
Route::get('/company/{company}/jobs', [CompanyController::class, 'showJobs'])->name('company.jobs');
Route::get('/company/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit');
Route::put('/company/{company}', [CompanyController::class, 'update'])->name('company.update');


/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
*/
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::delete('/admin/users/{user}', [UserController::class, 'destroyUser'])->name('users.destroy');

/*
|--------------------------------------------------------------------------
| Salary Routes
|--------------------------------------------------------------------------
*/
Route::get('/salaries', [SalariesController::class, 'index'])->name('salaries.index');

/*
|--------------------------------------------------------------------------
| Contact Routes
|--------------------------------------------------------------------------
*/
Route::get('/contact', function () {
    return view('contactus.show');
});
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/contact/index', [ContactController::class, 'index'])->name('contact.index');
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
Route::post('/contacts/{contact}/toggle-status', [ContactController::class, 'toggleStatus'])->name('contacts.toggleStatus');

/*
|--------------------------------------------------------------------------
| Report Routes
|--------------------------------------------------------------------------
*/
Route::get('/problem', function () {
    return view('reportproblem.ReportProblem');
});
Route::get('/problems', [ProblemController::class, 'index'])->name('problems.index');
Route::post('/report-problem', [ProblemController::class, 'store'])->name('report.problem');
Route::delete('/problems/{id}', [ProblemController::class, 'destroy'])->name('problems.destroy');

/*
|--------------------------------------------------------------------------
| Search Routes
|--------------------------------------------------------------------------
*/
Route::get('/search', [SearchController::class, 'search'])->name('jobs.search');
Route::get('/autocomplete', [SearchController::class, 'autocomplete']);
Route::get('/admin/job/search', [SearchController::class, 'adminjobs'])->name('admin.search');

Route::get('/pay', function () {
    return view('payments/paymentmethods');
});
Route::get('payments/create', function(){
    return view('payments/create');
});
Route::post('/payments/store', [PaymentController::class, 'store']);
Route::get('payments', [PaymentController::class, 'index'])->name('payment.index');
Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');

/*
|--------------------------------------------------------------------------
| Jobify OTP Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/otp/send',           [\App\Http\Controllers\Jobify\OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify',         [\App\Http\Controllers\Jobify\OtpController::class, 'verify'])->name('otp.verify');
Route::get('/employer/login',      [\App\Http\Controllers\Jobify\OtpController::class, 'employerLoginPage'])->name('employer.login.page');
Route::post('/employer/login',     [\App\Http\Controllers\Jobify\OtpController::class, 'employerLogin'])->name('employer.login');

/*
|--------------------------------------------------------------------------
| Jobify Platform Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Jobify\HomeController as JobifyHome;
use App\Http\Controllers\Jobify\PublicJobsController;
use App\Http\Controllers\Jobify\CandidateController;
use App\Http\Controllers\Jobify\CompanyController as JobifyCompany;
use App\Http\Controllers\Jobify\QAdminController;

// Jobify Public
Route::get('/find-jobs-home', [JobifyHome::class, 'index'])->name('jobs.browse.home');
Route::get('/find-jobs', [PublicJobsController::class, 'index'])->name('jobs.browse');
Route::get('/find-jobs/{uuid}', [PublicJobsController::class, 'show'])->name('jobs.browse.show');
Route::post('/find-jobs/{uuid}/apply', [PublicJobsController::class, 'apply'])->middleware('auth')->name('jobs.browse.apply');

// Jobify Candidate (auth required)
Route::middleware(['auth'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/onboarding', [CandidateController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [CandidateController::class, 'completeOnboarding'])->name('onboarding.complete');
    Route::post('/onboarding/parse-resume', [CandidateController::class, 'parseResume'])->name('onboarding.parse');
    Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
    Route::put('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');
    Route::post('/resume/upload', [CandidateController::class, 'uploadResume'])->name('resume.upload');
    Route::get('/applications', [CandidateController::class, 'applications'])->name('applications');
    Route::get('/saved-jobs', [CandidateController::class, 'savedJobs'])->name('saved-jobs');
    Route::post('/saved-jobs', [CandidateController::class, 'saveJob'])->name('saved-jobs.store');
    Route::delete('/saved-jobs/{savedJob}', [CandidateController::class, 'unsaveJob'])->name('saved-jobs.destroy');
    Route::get('/alerts', [CandidateController::class, 'alerts'])->name('alerts');
    Route::post('/alerts', [CandidateController::class, 'storeAlert'])->name('alerts.store');
    Route::delete('/alerts/{alert}', [CandidateController::class, 'deleteAlert'])->name('alerts.destroy');
    Route::get('/skill-gap', [CandidateController::class, 'skillGap'])->name('skill-gap');
    Route::post('/skill-gap/analyse', [CandidateController::class, 'skillGap'])->name('skill-gap.analyse');
    Route::get('/interview-prep', [CandidateController::class, 'interviewPrep'])->name('interview-prep');
    Route::post('/interview-prep/generate', [CandidateController::class, 'interviewPrep'])->name('interview-prep.generate');
    Route::get('/resume-generator', [CandidateController::class, 'resumeGenerator'])->name('resume-generator');
    Route::post('/resume-generator/generate', [CandidateController::class, 'resumeGenerator'])->name('resume-generator.generate');
});

// Jobify Company (auth required)
Route::middleware(['auth'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/setup', [JobifyCompany::class, 'setup'])->name('setup');
    Route::post('/setup', [JobifyCompany::class, 'store'])->name('setup.store');
    Route::get('/dashboard', [JobifyCompany::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [JobifyCompany::class, 'profile'])->name('profile');
    Route::put('/profile', [JobifyCompany::class, 'updateProfile'])->name('profile.update');
    Route::get('/jobs', [JobifyCompany::class, 'jobIndex'])->name('jobs');
    Route::get('/jobs/create', [JobifyCompany::class, 'jobCreate'])->name('jobs.create');
    Route::post('/jobs/ai-draft', [JobifyCompany::class, 'jobAiDraft'])->name('jobs.ai-draft');
    Route::post('/jobs', [JobifyCompany::class, 'jobStore'])->name('jobs.store');
    Route::get('/jobs/{job}', [JobifyCompany::class, 'jobShow'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [JobifyCompany::class, 'jobEdit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobifyCompany::class, 'jobUpdate'])->name('jobs.update');
    Route::patch('/jobs/{job}/publish', [JobifyCompany::class, 'jobPublish'])->name('jobs.publish');
    Route::delete('/jobs/{job}', [JobifyCompany::class, 'jobDestroy'])->name('jobs.destroy');
    Route::get('/applications', [JobifyCompany::class, 'applications'])->name('applications');
    Route::get('/applications/{application}', [JobifyCompany::class, 'reviewApplication'])->name('applications.review');
    Route::patch('/applications/{application}/move-stage', [JobifyCompany::class, 'moveStage'])->name('applications.move-stage');
    Route::patch('/applications/{application}/note', [JobifyCompany::class, 'saveNote'])->name('applications.note');
    Route::patch('/applications/{application}/reject', [JobifyCompany::class, 'rejectApplication'])->name('applications.reject');
    Route::post('/jobs/{job}/ai-rank', [JobifyCompany::class, 'aiRank'])->name('jobs.ai-rank');
    Route::get('/jobs/{job}/automations', [JobifyCompany::class, 'automations'])->name('jobs.automations');
    Route::post('/jobs/{job}/automations', [JobifyCompany::class, 'storeAutomation'])->name('jobs.automations.store');
    Route::patch('/automations/{automation}/toggle', [JobifyCompany::class, 'toggleAutomation'])->name('automations.toggle');
    Route::delete('/automations/{automation}', [JobifyCompany::class, 'destroyAutomation'])->name('automations.destroy');
    Route::post('/jobs/{job}/stages', [JobifyCompany::class, 'pipelineStages'])->name('jobs.stages.store');
    Route::patch('/stages/{stage}', [JobifyCompany::class, 'updateStage'])->name('stages.update');
    Route::delete('/stages/{stage}', [JobifyCompany::class, 'destroyStage'])->name('stages.destroy');
    Route::get('/departments', [JobifyCompany::class, 'departments'])->name('departments');
    Route::post('/departments', [JobifyCompany::class, 'storeDepartment'])->name('departments.store');
    Route::delete('/departments/{dept}', [JobifyCompany::class, 'destroyDepartment'])->name('departments.destroy');
    Route::get('/team', [JobifyCompany::class, 'team'])->name('team');
});

// Jobify Admin
Route::middleware(['auth', 'admin'])->prefix('platform-admin')->name('platform-admin.')->group(function () {
    Route::get('/', [QAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [QAdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle-status', [QAdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/moderation', [QAdminController::class, 'moderation'])->name('moderation');
    Route::patch('/jobs/{job}/moderate', [QAdminController::class, 'moderateJob'])->name('jobs.moderate');
    Route::patch('/orgs/{org}/moderate', [QAdminController::class, 'moderateOrg'])->name('orgs.moderate');
    Route::get('/settings', [QAdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [QAdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/job-taxonomy', [QAdminController::class, 'jobTaxonomy'])->name('job-taxonomy');
    Route::post('/job-taxonomy', [QAdminController::class, 'storeTaxonomy'])->name('job-taxonomy.store');
    Route::put('/job-taxonomy/{taxonomy}', [QAdminController::class, 'updateTaxonomy'])->name('job-taxonomy.update');
    Route::delete('/job-taxonomy/{taxonomy}', [QAdminController::class, 'destroyTaxonomy'])->name('job-taxonomy.destroy');
    Route::get('/email-templates', [QAdminController::class, 'emailTemplates'])->name('email-templates');
    Route::put('/email-templates/{template}', [QAdminController::class, 'updateEmailTemplate'])->name('email-templates.update');
    Route::get('/audit', [QAdminController::class, 'auditLog'])->name('audit');
    Route::get('/health', [QAdminController::class, 'health'])->name('health');
});