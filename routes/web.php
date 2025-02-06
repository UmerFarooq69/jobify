<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Job_taskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Job_taskController::class, 'index'])->name('jobs.welcome');


Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('Users.dashboard');
Route::get('/admin/index', [AdminController::class, 'index'])->name('admin.index');
Route::delete('/company/{company}', [AdminController::class, 'destroy'])->name('company.destroy');
Route::delete('/admin/jobs/{job}', [AdminController::class, 'destroyJob'])->name('admin.jobs.destroy');
Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
Route::get('/companies/{company}/jobs', [AdminController::class, 'showJobs'])->name('companies.show');
Route::get('/admin/jobs', [AdminController::class, 'Jobs'])->name('admin.jobs');
Route::get('/user/jobs', [DashboardController::class, 'Jobs'])->name('Users.jobs');
Route::get('/user/company', [DashboardController::class, 'company'])->name('Users.company');
Route::delete('/company/{company}', [DashboardController::class, 'destroy'])->name('company.destroy');
Route::delete('Users/applications/{application}', [DashboardController::class, 'destroyApplication'])->name('application.destroy');

Route::post('/job', [Job_taskController::class, 'create'])->name('jobs.create');
Route::get('/job', [Job_taskController::class, 'create'])->name('jobs.create');
Route::post('/job', [Job_taskController::class, 'store'])->name('jobs.store');
Route::get('/jobs/{job}/apply', [Job_taskController::class, 'apply'])->name('jobs.apply');
Route::post('/jobs/{job}/apply', [Job_taskController::class, 'submitApplication'])->name('jobs.submitApplication');

Route::get('/company', [CompanyController::class, 'index'])->name('companies');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
Route::get('/companycreate', [CompanyController::class, 'create'])->name('companies.create');
Route::get('/company/{company}/jobs', [CompanyController::class, 'showJobs'])->name('company.jobs');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/userscreate', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::patch('/admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
Route::delete('/User/jobs/{job}', [DashboardController::class, 'destroyJob'])->name('Users.jobs.destroy');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/salaries', [SalariesController::class, 'index'])->name('salaries.index');
Route::get('/jobs/{id}', [SalariesController::class, 'show'])->name('jobs.show');
Route::get('/careers', [CareerController::class, 'index'])->name('career.index');

Route::get('/applications', [AdminController::class, 'applications'])->name('applications.index');
Route::get('/user/applications', [DashboardController::class, 'applications'])->name('Users.application');
Route::delete('/applications/{application}', [AdminController::class, 'destroyApplication'])->name('applications.destroy');