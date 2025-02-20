<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Http\Resources\AppointmentResource;
use Exception;

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
            $request->validate([
                'patient_id' => 'required|string|exists:patients,id',
                'medical_staff_id' => 'required|string|exists:medical_staff,id',
                'clinic_id' => 'required|string|exists:clinics,id',
                'appointment_date' => 'required|date',
            ]);

            $appointment = Appointment::create([
                'patient_id' => $request->patient_id,
                'medical_staff_id' => $request->medical_staff_id,
                'clinic_id' => $request->clinic_id,
                'appointment_date' => $request->appointment_date,
                'status' => 'waiting',
            ]);
            return ApiResponse::success(new AppointmentResource($appointment), "Appointment created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create appointment", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $request->validate([
                'status' => 'required|in:waiting,confirmed,cancelled',
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
