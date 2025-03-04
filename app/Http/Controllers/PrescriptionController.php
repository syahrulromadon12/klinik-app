<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Helpers\ApiResponse;
use App\Http\Resources\PrescriptionResource;
use Illuminate\Validation\ValidationException;


class PrescriptionController extends Controller
{
    public function index()
    {
        try {
            $prescriptions = Prescription::with(['clinic', 'medicalStaff', 'patient', 'prescriptionItems.medicine'])->get();

            if ($prescriptions->isEmpty()) {
                return ApiResponse::error("No prescriptions found", 404);
            }

            return ApiResponse::success(PrescriptionResource::collection($prescriptions), "Prescriptions retrieved successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $prescription = Prescription::with(['clinic', 'medicalStaff', 'patient', 'prescriptionItems.medicine'])->findOrFail($id);

            return ApiResponse::success(new PrescriptionResource($prescription), "Prescription retrieved successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

}
