<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::with('assignedUser')->get();
    }

    public function store(Request $request)
    {
        return Task::create([
            'title' => $request->title,
            'status' => $request->status,
            'user_id' => $request->assigned_to, // Changed from assigned_to
            'due_date' => $request->due_date
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'status' => $request->status
        ]);

        return $task;
    }

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}