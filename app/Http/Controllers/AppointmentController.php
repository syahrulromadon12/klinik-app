<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Http\Resources\AppointmentResource;
use Exception;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    Public function index()
    {
        try {
            $appointments = Appointment::all();
            if ($appointments->isEmpty()) {
                return ApiResponse::error("No appointments found", 404);
            }
            return ApiResponse::success(AppointmentResource::collection($appointments), "Appointments retrieved successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve appointments", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi request
            $validated = $request->validate([
                'patient_id' => 'required|string|exists:patients,id',
                'medical_staff_id' => 'required|string|exists:medical_staff,id',
                'clinic_id' => 'required|string|exists:clinics,id',
                'appointment_date' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $errorMessage = null;
            $now = now();
            $appointment_date = Carbon::parse($request->appointment_date);

            if ($appointment_date->lessThan($now)) {
                $errorMessage = "Appointment date must be in the future";
            } elseif (!$appointment_date->between($appointment_date->copy()->setTime(8, 0, 0), $appointment_date->copy()->setTime(17, 0, 0))) {
                $errorMessage = "Appointment time must be between 08:00 and 17:00";
            } elseif ($appointment_date->between($appointment_date->copy()->setTime(12, 0, 0), $appointment_date->copy()->setTime(13, 0, 0))) {
                $errorMessage = "Appointment time must not be between 12:00 and 13:00";
            }

            if ($errorMessage) {
                return ApiResponse::error($errorMessage, 422);
            }

            // Cek apakah medical staff sudah ada janji pada waktu yang sama
            if (Appointment::where('medical_staff_id', $validated['medical_staff_id'])
                ->whereDate('appointment_date', $appointment_date->toDateString())
                ->whereTime('appointment_date', $appointment_date->toTimeString())
                ->exists()) {
                return ApiResponse::error("Medical staff is not available at the selected time", 422);
            }

            // Simpan ke database
            $appointment = Appointment::create([
                'patient_id' => $validated['patient_id'],
                'medical_staff_id' => $validated['medical_staff_id'],
                'clinic_id' => $validated['clinic_id'],
                'appointment_date' => $appointment_date,
                'status' => 'waiting',
            ]);

            return ApiResponse::success(new AppointmentResource($appointment), "Appointment created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error("Appointment Store Error: " . $e->getMessage());
            return ApiResponse::error("Failed to create appointment", ['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            return ApiResponse::success(new AppointmentResource($appointment), "Appointment retrieved successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Appointment not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve appointment", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $request->validate([
                'appointment_date' => 'required|date_format:Y-m-d H:i:s',
                'status' => 'required|in:waiting,done,cancelled',
            ]);
            $appointment->update($request->all());
            return ApiResponse::success(null, "Appointment updated successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Appointment not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update appointment", 500);
        }
    }

    public function destroy($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->delete();
            return ApiResponse::success(null, "Appointment deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Appointment not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete appointment", 500);
        }
    }
}
