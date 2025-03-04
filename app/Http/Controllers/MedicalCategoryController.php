<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalCategory;
use App\Http\Resources\MedicalCategoryResource;
use App\Helpers\ApiResponse;


class MedicalCategoryController extends Controller
{
    public function index()
    {
        try{
            $categories = MedicalCategory::all();

            if ($categories->isEmpty()) {
                return ApiResponse::error("No medical categories found", 404);
            }
            return ApiResponse::success(MedicalCategoryResource::collection($categories), "Medical categories retrieved successfully", 200);
        }catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
            ]);

            MedicalCategory::create($validated);

            return ApiResponse::success(null, "Medical category created successfully", 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $category = MedicalCategory::find($id);
            if (!$category) {
                return ApiResponse::error("Medical category not found",404, 404);
            }

            return ApiResponse::success(new MedicalCategoryResource($category), "Medical category retrieved successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = MedicalCategory::find($id);
            if (!$category) {
                return ApiResponse::error("Medical category not found",404, 404);
            }

            $validated = $request->validate([
                'name' => 'required|string',
            ]);

            $category->update($validated);

            return ApiResponse::success(null, "Medical category updated successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = MedicalCategory::find($id);
            if (!$category) {
                return ApiResponse::error("Medical category not found",404, 404);
            }

            $category->delete();

            return ApiResponse::success(null, "Medical category deleted successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    } 
}
