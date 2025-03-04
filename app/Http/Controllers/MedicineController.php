<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Http\Resources\MedicineResource;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicineController extends Controller
{
    public function index()
    {
        try{
            $medicines = Medicine::with('medicalCategory')->get();

            if ($medicines->isEmpty()) {
                return ApiResponse::error("No medicines found", 404);
            }

            return ApiResponse::success(MedicineResource::collection($medicines), "Medicines retrieved successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'medical_category_id' => 'required|string|exists:medical_categories,id',
            ]);

            Medicine::create($validated);

            return ApiResponse::success(null, "Medicine created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $medicine = Medicine::with('medicalCategory')->find($id);
            if (!$medicine) {
                return ApiResponse::error("Medicine not found",404, 404);
            }

            return ApiResponse::success(new MedicineResource($medicine), "Medicine retrieved successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $medicine = Medicine::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'medical_category_id' => 'required|string|exists:medical_categories,id',
            ]);

            $medicine->update($validated);

            return ApiResponse::success(null, "Medicine updated successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Cari data dengan find(), jika tidak ditemukan return 404
            $medicine = Medicine::findOrFail($id);

            if (!$medicine) {
                return ApiResponse::error("Medicine not found", 404);
            }

            // Cek apakah obat digunakan dalam prescriptionItems
            if ($medicine->prescriptionItems()->exists()) {
                return ApiResponse::error("Medicine cannot be deleted because it is used in a prescription", 400);
            }

            // Hapus obat
            $medicine->delete();

            return ApiResponse::success(null, "Medicine deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Medicine not found", 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
