<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\AppointmentController;
use App\Http\Middleware\ApiExceptionMiddleware;

// Route untuk mendapatkan user yang sedang login
Route::middleware(['auth:sanctum', ApiExceptionMiddleware::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::prefix('/users')->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/role/{role_id}', [UserController::class, 'userByRole']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::patch('/{id}/restore', [UserController::class, 'restoreUser']);
        Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete']);
    });
    Route::get('/search-users', [UserController::class, 'search']);

    // Patients
    Route::prefix('/patients')->group(function () {
        Route::get('/', [PatientController::class, 'index']);
        Route::get('/{id}', [PatientController::class, 'show']);
        Route::post('/', [PatientController::class, 'store']);
        Route::put('/{id}', [PatientController::class, 'update']);
        Route::delete('/{id}', [PatientController::class, 'destroy']);
        Route::patch('/{id}/restore', [PatientController::class, 'restore']);
        Route::delete('/{id}/force-delete', [PatientController::class, 'forceDelete']);
    });
    Route::get('/search-patients', [PatientController::class, 'search']);

    // Appointments
    Route::prefix('/appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::put('/{id}', [AppointmentController::class, 'update']);
        Route::delete('/{id}', [AppointmentController::class, 'destroy']);
    });

    // Roles
    Route::prefix('/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
        Route::delete('/{id}/force-delete', [RoleController::class, 'forceDelete']);
        Route::patch('/{id}/restore', [RoleController::class, 'restore']);
    });

    //clinic
    Route::prefix('/clinics')->group(function () {
        Route::get('/', [ClinicController::class, 'index']);
        Route::post('/', [ClinicController::class, 'store']);
        Route::put('/{id}', [ClinicController::class, 'update']);
        Route::delete('/{id}', [ClinicController::class, 'destroy']);
    });

    // Queue
    Route::prefix('/queues')->group(function () {
        Route::get('/', [QueueController::class, 'index']);
        Route::post('/', [QueueController::class, 'store']);
        Route::put('/{id}', [QueueController::class, 'update']);
        Route::delete('/{id}', [QueueController::class, 'destroy']);
    });

    Route::get('/trashed-users', [UserController::class, 'showTrashedUser']);
    Route::get('/trashed-patients', [PatientController::class, 'showTrashedPatient']);
});

// Auth (Login tetap di luar middleware)
Route::post('/login', [AuthController::class, 'login'])->name('login');



