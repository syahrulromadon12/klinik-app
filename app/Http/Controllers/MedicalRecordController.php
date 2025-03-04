<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Helpers\ApiResponse;
use App\Http\Resources\MedicalRecordResource;
use App\Http\Resources\MedicalRecordDetailResource;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicalRecordController extends Controller
{
    public function index()
    {
        try {
            $medicalRecords = MedicalRecord::with(['patient', 'medicalStaff', 'appointment', 'clinic', 'prescription'])->get();

            if ($medicalRecords->isEmpty()) {
                return ApiResponse::error("No medical records found", 404);
            }

            return ApiResponse::success(MedicalRecordResource::collection($medicalRecords), "Medical records retrieved successfully", 200);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->getStatusCode($e));
        }
    }

    public function show($id)
    {
        try {
            $medicalRecord = MedicalRecord::with(['patient', 'medicalStaff', 'appointment', 'clinic', 'prescription'])->findOrFail($id);

            return ApiResponse::success(new MedicalRecordResource($medicalRecord), "Medical record retrieved successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Medical Record not found", [], 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to show Medical Record", $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|string|exists:patients,id',
                'medical_staff_id' => 'required|string|exists:medical_staff,id',
                'appointment_id' => 'required|string|exists:appointments,id',
                'clinic_id' => 'required|string|exists:clinics,id',
                'diagnosis' => 'required|string',
                'symptoms' => 'required|string',
                'notes' => 'required|string',
                'prescription_id' => 'required|string|exists:prescriptions,id',
            ]);

            MedicalRecord::create($validated);

            return ApiResponse::success(null, "Medical record created successfully", 201);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->getStatusCode($e));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|string|exists:patients,id',
                'medical_staff_id' => 'required|string|exists:medical_staff,id',
                'appointment_id' => 'required|string|exists:appointments,id',
                'clinic_id' => 'required|string|exists:clinics,id',
                'diagnosis' => 'required|string',
                'symptoms' => 'required|string',
                'notes' => 'required|string',
                'prescription_id' => 'required|string|exists:prescriptions,id',
            ]);

            $medicalRecord = MedicalRecord::findOrFail($id);
            $medicalRecord->update($validated);

            return ApiResponse::success(null, "Medical record updated successfully", 201);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->getStatusCode($e));
        }
    }

    public function destroy($id)
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);
            $medicalRecord->delete();

            return ApiResponse::success(null, "Medical record deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Medical Record not found", null, 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to show Medical Record", $e->getMessage(), 500);
        }
    }

    public function showTrashedMedicalRecord()
    {
        try {
            $medicalRecords = MedicalRecord::onlyTrashed()->get();
            if ($medicalRecords->isEmpty()) {
                return ApiResponse::error("No trashed medical records found", 404);
            }
            return ApiResponse::success(MedicalRecordResource::collection($medicalRecords), "Trashed medical records retrieved successfully", 200);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->getStatusCode($e));
        }
    }

    public function forceDelete($id)
{
    try {
        $medicalRecord = MedicalRecord::onlyTrashed()->findOrFail($id);
        $medicalRecord->forceDelete();
        return ApiResponse::success(null, "Medical record permanently deleted successfully", 200);
    } catch (ModelNotFoundException $e) {
        return ApiResponse::error("Medical Record not found", [], 404);
    } catch (\Exception $e) {
        return ApiResponse::error("Failed to permanently delete Medical Record", $e->getMessage(), 500);
    }
}


    public function restore($id)
    {
        try {
            $medicalRecord = MedicalRecord::withTrashed()->findOrFail($id);
            $medicalRecord->restore();

            return ApiResponse::success(null, "Medical record restored successfully", 200);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->getStatusCode($e));
        }
    }

    public function search(Request $request)
    {
        try {
            $medicalRecords = MedicalRecord::join('patients', 'patients.id', '=', 'medical_records.patient_id')
                ->where('patients.name', 'like', '%' . $request->search . '%')
                ->orWhere('medical_records.diagnosis', 'like', '%' . $request->search . '%')
                ->orWhere('medical_records.symptoms', 'like', '%' . $request->search . '%')
                ->orWhere('medical_records.notes', 'like', '%' . $request->search . '%')
                ->select('medical_records.*') // Ambil hanya kolom dari `medical_records`
                ->get();

            if ($medicalRecords->isEmpty()) {
                return ApiResponse::error("No medical records found", 404);
            }

            return ApiResponse::success(MedicalRecordResource::collection($medicalRecords), "Medical records retrieved successfully", 200);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->getStatusCode($e));
        }
    }

    private function getStatusCode($e)
    {
        if ($e instanceof ValidationException) {
            return 422;
        } elseif ($e instanceof HttpException) {
            return $e->getStatusCode();
        } else {
            return 500;
        }
    }
}
