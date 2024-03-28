<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $tasks = Tasks::all();
        return $data = new TaskResource($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Validations
        // 1. Task are required
        // 2. Task should be max 255 characters.
        // 3. File should be image or pdf, size max 2 mb

        // Storing tasks
        try {
            $title = $request->title;
            $filename = $request->filename;

            $task = ["title" => $title];

            if ($filename) {
                $filepath = $request->file('file')->store('attachments');
                $task += [
                    "filename" => $filename,
                    "filepath" => $filepath
                ];
            }

            Tasks::create($task);

            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->errorResponse();
        }

        // Returning proper api response
    }

    /**
     * Display the specified resource.
     */
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        
        try {
            $task_id = Crypt::decryptString($id);
            $title = $request->title;
            $filename = $request->filename;

            $task = ["title" => $title];

            if ($filename) {
                $filepath = $request->file('file')->store('attachments');
                $task += [
                    "filename" => $filename,
                    "filepath" => $filepath
                ];
            }

            Tasks::where("id", $task_id)->update($task);

            return $this->successResponse();
        } catch (\Throwable $th) {
            return $th;
            return $this->errorResponse();
        }
    }

    /**
     * Update the specified resource status in storage.
     */
    public function updateTaskStatus(Request $request) {
        $task_id = Crypt::decryptString($request->id);
        $task_status = $request->status;

        try {
            Tasks::where("id", $task_id)->update(["status" => $task_status]);

            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->errorResponse();
        }
    }

    /**
     * Download the attachment of the specified resource from storage.
     */
    public function downloadTaskAttachment($id) {
        $task_id = Crypt::decryptString($id);

        try {
            $task = Tasks::where("id", $task_id)->first();

            return $file = Storage::download($task['filepath']);

        } catch (\Throwable $th) {
            return $this->errorResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        try {
            $task_id = Crypt::decryptString($id);
            return Tasks::where("id", 4)->restore();
            $taskDeleted = Tasks::destroy($task_id);
    
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->errorResponse();
        }
    }

    public function successResponse ($statusCode = Response::HTTP_OK) {
        return response()->json([
            'data' => [
                'status' => true
            ]
        ], $statusCode);
    }
    public function errorResponse ($statusCode = Response::HTTP_OK) {
        return response()->json([
            'data' => [
                'status' => false
            ]
        ], $statusCode);
    }
}
