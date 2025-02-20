<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ApiResponse;
use App\Http\Resources\ClinicResource;
use Exception;

class ClinicController extends Controller
{
    public function index()
    {
        try {
            $clinics = Clinic::all();
            if ($clinics->isEmpty()) {
                return ApiResponse::error("No clinics found", 404);
            }
            return ApiResponse::success(ClinicResource::collection($clinics), "Clinics retrieved successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve clinics", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:32|unique:clinics',
                'code' => 'required|string|max:32|unique:clinics',
            ]);
            $clinic = Clinic::create($request->all());
            return ApiResponse::success(null, "Clinic created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create clinic", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $clinic = Clinic::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:32',
                'code' => 'required|string|max:32',
            ]);
            $clinic->update($request->all());
            return ApiResponse::success(null, "Clinic updated successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Clinic not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete clinic", $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $clinic = Clinic::findOrFail($id);
            $clinic->delete();
            return ApiResponse::success(null, "Clinic deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Clinic not found", null, 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete clinic", $e->getMessage(), 500);
        }
    }
}
