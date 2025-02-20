<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MedicalStaff;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserDetailResource;
use Illuminate\Support\Str;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('role')->get();

            if ($users->isEmpty()) {
                return ApiResponse::error("No users found", 404);
            }

            return ApiResponse::success(UserResource::collection($users), "Users retrieved successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to retrieve users");
        }
    }

    public function store(Request $request) //err null work_schedule
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'role_id' => 'required|string|max:255',
                'photo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'nik_number' => 'nullable|string|max:255|unique:users',
                'kis_number' => 'nullable|string|max:255|unique:users',
                'blood_type' => 'nullable|in:A,B,AB,O,unknown',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:15',
                'insurance_number' => 'nullable|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'clinic_id' => 'required|uuid', 
                'license_number' => 'required|string|max:255|unique:medical_staff',
                'specialization' => 'nullable|string|max:255',
                'work_schedule' => 'nullable|json',
                'experience_years' => 'nullable|integer',
                'education' => 'nullable|string|max:255',
                'consultation_fee' => 'nullable|numeric',
            ]);

            // Start a database transaction to ensure both records are created successfully
            \DB::beginTransaction();

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo_path')) {
                // Store photo in the 'users' directory under 'public' disk
                $photoPath = $request->file('photo_path')->storeAs(
                    'users/' . Str::uuid(), // Create a unique folder for each user
                    $request->file('photo_path')->getClientOriginalName(),
                    'public' // Ensure it's saved in the public disk
                );
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'role_id' => $request->role_id,
                'photo_path' => $photoPath, // Store the photo path
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nik_number' => $request->nik_number,
                'kis_number' => $request->kis_number,
                'blood_type' => $request->blood_type ?? 'unknown',
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'insurance_number' => $request->insurance_number,
                'status' => 'active', // Default active status
                'password' => bcrypt($request->password), // Ensure password is hashed
            ]);

            // Create the medical staff record linked to the user
            $medicalStaff = MedicalStaff::create([
                'user_id' => $user->id,
                'license_number' => $request->license_number,
                'specialization' => $request->specialization,
                'work_schedule' => $request->work_schedule ? json_encode(json_decode($request->work_schedule, true)) : null,
                'experience_years' => $request->experience_years,
                'education' => $request->education,
                'consultation_fee' => $request->consultation_fee,
                'clinic_id' => $request->clinic_id, 
            ]);

            // Commit the transaction to save both user and medical staff
            \DB::commit();

            // Return a success response with the created user and medical staff
            return ApiResponse::success(new UserDetailResource($user), "User created successfully", 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            // Log the exception and return a generic error response
            Log::error($e->getMessage());
            \DB::rollBack();  // Rollback transaction in case of failure
            return ApiResponse::error("Failed to create user", [], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'sometimes|required|string|max:15',
                'address' => 'sometimes|required|string|max:255',
                'role_id' => 'sometimes|required|string|max:255',
                'photo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                'date_of_birth' => 'sometimes|required|date',
                'gender' => 'sometimes|required|in:male,female,other',
                'nik_number' => 'nullable|string|max:255|unique:users,nik_number,' . $user->id,
                'kis_number' => 'nullable|string|max:255|unique:users,kis_number,' . $user->id,
                'blood_type' => 'nullable|in:A,B,AB,O,unknown',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:15',
                'insurance_number' => 'nullable|string|max:255|unique:users,insurance_number,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'clinic_id' => 'sometimes|required|uuid', 
                'license_number' => 'sometimes|required|string|max:255|unique:medical_staff,license_number,' . $user->medicalStaff->id,
                'specialization' => 'nullable|string|max:255',
                'work_schedule' => 'nullable|json',
                'experience_years' => 'nullable|integer',
                'education' => 'nullable|string|max:255',
                'consultation_fee' => 'nullable|numeric',
            ]);

            // Start a database transaction
            \DB::beginTransaction();

            // Handle photo upload if provided
            if ($request->hasFile('photo_path')) {
                // Delete old photo if exists
                if ($user->photo_path) {
                    Storage::disk('public')->delete($user->photo_path);
                }

                // Store new photo
                $photoPath = $request->file('photo_path')->storeAs(
                    'users/' . Str::uuid(),
                    $request->file('photo_path')->getClientOriginalName(),
                    'public'
                );
                $user->photo_path = $photoPath;
            }

            // Update user data
            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'phone_number' => $request->phone_number ?? $user->phone_number,
                'address' => $request->address ?? $user->address,
                'role_id' => $request->role_id ?? $user->role_id,
                'date_of_birth' => $request->date_of_birth ?? $user->date_of_birth,
                'gender' => $request->gender ?? $user->gender,
                'nik_number' => $request->nik_number ?? $user->nik_number,
                'kis_number' => $request->kis_number ?? $user->kis_number,
                'blood_type' => $request->blood_type ?? $user->blood_type,
                'emergency_contact_name' => $request->emergency_contact_name ?? $user->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone ?? $user->emergency_contact_phone,
                'insurance_number' => $request->insurance_number ?? $user->insurance_number,
            ]);

            // Update medical staff data
            // Pastikan user memiliki medical staff
$medicalStaff = $user->medicalStaff;

if ($medicalStaff) {
    $medicalStaff->update([
        'license_number' => $request->license_number ?? $medicalStaff->license_number,
        'specialization' => $request->specialization ?? $medicalStaff->specialization,
        'work_schedule' => $request->work_schedule ? json_encode(json_decode($request->work_schedule, true)) : $medicalStaff->work_schedule,
        'experience_years' => $request->experience_years ?? $medicalStaff->experience_years,
        'education' => $request->education ?? $medicalStaff->education,
        'consultation_fee' => $request->consultation_fee ?? $medicalStaff->consultation_fee,
        'clinic_id' => $request->clinic_id ?? $medicalStaff->clinic_id,
    ]);
} else {
    return ApiResponse::error("Medical staff data not found for this user.", [], 404);
}
         

            // Commit the transaction
            \DB::commit();

            return ApiResponse::success(new UserDetailResource($user), "User updated successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('role', 'medicalStaff')->findOrFail($id);
            return ApiResponse::success(new UserDetailResource($user), "User retrieved successfully");
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("User not found", [], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to retrieve user", [], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return ApiResponse::success(null, "User deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("User not found",[], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to delete user", [], 500);
        }
    }

    public function showTrashedUser()
    {
        try {
            $users = User::with('role')->onlyTrashed()->get();

            if ($users->isEmpty()) {
                return ApiResponse::error("No trashed users found",[], 404);
            }

            return ApiResponse::success(UserResource::collection($users), "Trashed users retrieved successfully");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to retrieve trashed users", [], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->forceDelete();
            return ApiResponse::success(null, "User deleted permanently");
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("User not found or not deleted",[], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to permanently delete user", [], 500);
        }
    }

    public function restoreUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return ApiResponse::success(new UserResource($user), "User restored successfully");
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("User not found",[], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to restore user");
        }
    }

    public function userByRole($role_id)
    {
        try {
            $users = User::with('role')->where('role_id', $role_id)->get();
            return ApiResponse::success(UserResource::collection($users), "Users retrieved successfully");
        } catch(ModelNotFoundException $e) {
            return ApiResponse::error("No users found",[], 404);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error("Failed to retrieve users by role", 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $searchTerm = $request->query('query'); // Mengambil parameter pencarian

            if (!$searchTerm) {
                return ApiResponse::error("No search term provided", [], 400);
            }

            // Jika input adalah UUID, cari berdasarkan ID
            if (Str::isUuid($searchTerm)) {
                $user = User::where('id', $searchTerm)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$user) {
                    return ApiResponse::error("User not found", [], 404);
                }

                return ApiResponse::success(new UserDetailResource($user), "User retrieved successfully");
            }

            // Jika bukan UUID, cari berdasarkan name, nik_number, atau kis_number
            $users = User::whereNull('deleted_at')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('nik_number', 'like', '%' . $searchTerm . '%')
                        ->orWhere('kis_number', 'like', '%' . $searchTerm . '%');
                })
                ->get();

            if ($users->isEmpty()) {
                return ApiResponse::error("No users found", [], 404);
            }

            return ApiResponse::success(UserResource::collection($users), "Users retrieved successfully");

        } catch (\Exception $e) {
            Log::error("Search error: " . $e->getMessage()); 
            return ApiResponse::error("Something went wrong", [], 500);
        }
    }
};