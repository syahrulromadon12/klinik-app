<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ApiResponse;
use App\Http\Resources\QueueResource;
use Exception;

class QueueController extends Controller
{
    public function index()
    {
        try {
            $queues = Queue::with('clinic')->get();
            if ($queues->isEmpty()) {
                return ApiResponse::error("No queues found", 404);
            }
            return ApiResponse::success(QueueResource::collection($queues), "Queues retrieved successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 400);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve queues", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|string|exists:clinics,id',
            ]);

            $today = now()->format('Y-m-d');
            $clinicId = $request->clinic_id;

            // Get the latest queue number created today for the clinic
            $latestQueue = Queue::where('clinic_id', $clinicId)
                                ->whereDate('created_at', $today)
                                ->orderByDesc('queue_number')
                                ->first();

            // If there's no queue today, start from 1, otherwise increment the last queue number
            $queueNumber = $latestQueue ? str_pad($latestQueue->queue_number + 1, 3, '0', STR_PAD_LEFT) : '001';

            // Create the new queue with the calculated queue number and default status "waiting"
            $queue = Queue::create([
                'clinic_id' => $clinicId,
                'queue_number' => $queueNumber,
                'status' => 'waiting', 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return ApiResponse::success(new QueueResource($queue), "Queue created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create queue", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $queue = Queue::findOrFail($id);
            $request->validate([
                'status' => 'required|string|in:waiting,called,done',
            ]);
            $queue->update($request->all());
            return ApiResponse::success(null, "Queue updated successfully", 200);
        } catch (ValidationException $e) {
            return ApiResponse::error("Validation error", $e->errors(), 422);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Queue not found", 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update queue", 500);
        }
    }

    public function destroy($id)
    {
        try {
            $clinic = Queue::findOrFail($id);
            $clinic->delete();
            return ApiResponse::success(null, "Queue deleted successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Queue not found", null, 404);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete queue", $e->getMessage(), 500);
        }
    }
}
