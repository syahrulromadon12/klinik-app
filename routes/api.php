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
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicalCategoryController;
use App\Http\Middleware\ApiExceptionMiddleware;

// Route untuk mendapatkan user yang sedang login
Route::middleware(['auth:sanctum', ApiExceptionMiddleware::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::prefix('/users')->group(function () {
        Route::middleware('role:Administration')->group(function () {
        });
        Route::post('/', [UserController::class, 'store']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/role/{role_id}', [UserController::class, 'userByRole']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::patch('/{id}/restore', [UserController::class, 'restoreUser']);
        Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete']);
    });
    
    // Patients
    Route::prefix('/patients')->group(function () {
        Route::get('/', [PatientController::class, 'index']);
        Route::get('/{id}', [PatientController::class, 'show']);
        Route::middleware('role:Receptionist')->group(function () {
        });
        Route::post('/', [PatientController::class, 'store']);
        Route::put('/{id}', [PatientController::class, 'update']);
        Route::delete('/{id}', [PatientController::class, 'destroy']);
        Route::patch('/{id}/restore', [PatientController::class, 'restore']);
        Route::delete('/{id}/force-delete', [PatientController::class, 'forceDelete']);
    });
    
    // Appointments
    Route::prefix('/appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::put('/{id}', [AppointmentController::class, 'update']);
        Route::delete('/{id}', [AppointmentController::class, 'destroy']);
    });
    
    //medical records
    Route::prefix('/medical-records')->group(function () {
        Route::get('/', [MedicalRecordController::class, 'index']);
        Route::post('/', [MedicalRecordController::class, 'store']);
        Route::get('/{id}', [MedicalRecordController::class, 'show']);
        Route::put('/{id}', [MedicalRecordController::class, 'update']);
        Route::delete('/{id}', [MedicalRecordController::class, 'destroy']);
        Route::patch('/{id}/restore', [MedicalRecordController::class, 'restore']);
        Route::delete('/{id}/force-delete', [MedicalRecordController::class, 'forceDelete']);
    });
    
    
    // Prescriptions
    Route::prefix('/prescriptions')->group(function () {
        Route::get('/', [PrescriptionController::class, 'index']);
        Route::get('/{id}', [PrescriptionController::class, 'show']);
        Route::post('/', [PrescriptionController::class, 'store']);
        Route::put('/{id}', [PrescriptionController::class, 'update']);
        Route::delete('/{id}', [PrescriptionController::class, 'destroy']);
    });
    
    //medicine
    Route::prefix('/medicines')->group(function () {
        Route::get('/', [MedicineController::class, 'index']);
        Route::get('/{id}', [MedicineController::class, 'show']);
        Route::post('/', [MedicineController::class, 'store']);
        Route::put('/{id}', [MedicineController::class, 'update']);
        Route::delete('/{id}', [MedicineController::class, 'destroy']);
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

    //Medical Category
    Route::prefix('/medical-categories')->group(function () {
        Route::get('/', [MedicalCategoryController::class, 'index']);
        Route::post('/', [MedicalCategoryController::class, 'store']);
        Route::get('/{id}', [MedicalCategoryController::class, 'show']);
        Route::put('/{id}', [MedicalCategoryController::class, 'update']);
        Route::delete('/{id}', [MedicalCategoryController::class, 'destroy']);
    });
    
    // Search
    Route::get('/search-users', [UserController::class, 'search']);
    Route::get('/search-patients', [PatientController::class, 'search']);
    Route::get('/search-medical-records', [MedicalRecordController::class, 'search']);
    
    // Trashed
    Route::get('/trashed-users', [UserController::class, 'showTrashedUser']);
    Route::get('/trashed-patients', [PatientController::class, 'showTrashedPatient']);
    Route::get('/trashed-medical-records', [MedicalRecordController::class, 'showTrashedMedicalRecord']);
});

// Auth (Login tetap di luar middleware)
Route::post('/login', [AuthController::class, 'login'])->name('login');



