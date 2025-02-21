<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Job_taskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [Job_taskController::class, 'index'])->name('jobs.welcome');
Route::get('/careers', [CareerController::class, 'index'])->name('career.index');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Requires Authentication & Admin Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/index', [AdminController::class, 'index'])->name('admin.index');

    // Jobs Management
    Route::get('/admin/jobs', [AdminController::class, 'Jobs'])->name('admin.jobs');
    Route::delete('/admin/jobs/{job}', [AdminController::class, 'destroyJob'])->name('admin.jobs.destroy');

    // User Management
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::patch('/admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    // Company Management
    Route::delete('/company/{company}', [AdminController::class, 'destroy'])->name('company.destroy');
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
    Route::get('/user/jobs', [DashboardController::class, 'Jobs'])->name('Users.jobs');
    Route::delete('/user/jobs/{job}', [DashboardController::class, 'destroyJob'])->name('Users.jobs.destroy');

    // Company
    Route::get('/user/company', [DashboardController::class, 'company'])->name('Users.company');
    Route::delete('/company/{company}', [DashboardController::class, 'destroy'])->name('company.destroy');

    // Applications
    Route::get('/user/applications', [DashboardController::class, 'applications'])->name('Users.application');
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

/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
*/
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

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
    return view('contactus.contact');
});
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/contact/index', [ContactController::class, 'index'])->name('contact.index');
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

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