<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ApiResponse;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all();
            if ($roles->isEmpty()) {
                return ApiResponse::error("No roles found", 404);
            }
            return ApiResponse::success(RoleResource::collection($roles), "Roles retrieved successfully", 200);
        }catch(validationException $e){
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve roles", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:32|unique:roles',
            ]);

            $role = Role::create($request->all());
            return ApiResponse::success(null, "Role created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create role", 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return ApiResponse::success(null, "Role deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Role not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete role", 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $role = Role::withTrashed()->findOrFail($id);
            $role->forceDelete();
            return ApiResponse::success(null, "Role permanently deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Role not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to permanently delete role", 500);
        }
    }

    public function restore($id)
    {
        try {
            $role = Role::withTrashed()->findOrFail($id);
            $role->restore();
            return ApiResponse::success(null, "Role restored successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Role not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to restore role", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:32|unique:roles,name,' . $id,
            ]);

            $role = Role::findOrFail($id);
            $role->update($request->all());
            return ApiResponse::success(null, "Role updated successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Role not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update role", 500);
        }
    }
}
