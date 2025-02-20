<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patients;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\PatientResource;
use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index()
    {
        try {
            $patients = Patients::all();
            return ApiResponse::success(PatientResource::collection($patients), "Patients retrieved successfully");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $patient = Patients::find($id);
            if (!$patient) {
                return ApiResponse::error("Patient not found", 404);
            }
            return ApiResponse::success(new PatientResource($patient), "Patient retrieved successfully");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'kis_number' => 'required|string|max:255',
                'nik_number' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:patients',
                'phone_number' => 'required|string|max:15',
                'date_of_birth' => 'required|date',
                'address' => 'required|string|max:255',
                'gender' => 'required|string|max:10',
                'blood_type' => 'required|string|max:3',
                'allergy' => 'nullable|string',
                'job' => 'nullable|string|max:255',
                'photo_path' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
            ]);

            // Simpan foto ke storage
            $photoPath = $request->file('photo_path')->storeAs(
                'patients',
                uniqid('patient_') . '.' . $request->file('photo_path')->getClientOriginalExtension(),
                'public'
            );

            // Simpan data pasien ke dalam database
            Patients::create([
                'kis_number' => $request->kis_number,
                'nik_number' => $request->nik_number,
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'gender' => $request->gender,
                'blood_type' => $request->blood_type,
                'allergy' => $request->allergy,
                'job' => $request->job,
                'photo_path' => $photoPath,
            ]);

            // Response sukses tanpa mengembalikan data pasien
            return ApiResponse::success(null, "Patient created successfully", 201);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $patient = Patients::find($id);
            if (!$patient) {
                return ApiResponse::error("Patient not found", 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:patients,email,' . $id,
                'phone_number' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'photo_path' => 'required|string',
            ]);

            $patient->update($validated);
            return ApiResponse::success(new PatientResource($patient), "Patient updated successfully");
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $patient = Patients::find($id);
            if (!$patient) {
                return ApiResponse::error("Patient not found", 404);
            }
            $patient->delete();
            return ApiResponse::success(null, "Patient deleted successfully");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function showTrashedPatient()
    {
        try {
            $patients = Patients::onlyTrashed()->get();

            if ($patients->isEmpty()) {
                return ApiResponse::error("No trashed patients found", 404);
            }

            return ApiResponse::success($patients, "Trashed patients retrieved successfully");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $patient = Patients::onlyTrashed()->find($id);
            if (!$patient) {
                return ApiResponse::error("Patient not found or not deleted", 404);
            }
            $patient->forceDelete();
            return ApiResponse::success(null, "Patient permanently deleted");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function restore($id)
    {
        try {
            $patient = Patients::onlyTrashed()->find($id);
            if (!$patient) {
                return ApiResponse::error("Patient not found or not deleted", 404);
            }
            $patient->restore();
            return ApiResponse::success(null, "Patient restored successfully");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->query('query'); // Get the search term from the query parameter (change from 'search' to 'query')

        if (!$searchTerm) {
            return response()->json(['error' => 'No search term provided'], 400);
        }

        // If you're searching by ID and expect a UUID, ensure it's a valid UUID
        if (Str::isUuid($searchTerm)) {
            $patient = Patients::where('id', $searchTerm)
                ->whereNull('deleted_at')
                ->first();

            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }

            return response()->json($patient);
        }

        // If it's not a UUID, perhaps you're searching by name or another field
        $patients = Patients::where('name', 'like', '%' . $searchTerm . '%')
            ->whereNull('deleted_at')
            ->get();

        return response()->json($patients);
    }
}
