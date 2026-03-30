<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // બધા ટાસ્ક બતાવવા
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        // નવો ટાસ્ક સેવ કરવો
        Task::create([
            'title' => $request->title,
            'status' => TaskStatus::PENDING // Default Enum વાપરવું
        ]);

        return back();
    }
    public function update(Request $request, Task $task)
{
    $task->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status updated successfully!');
}
    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted successfully!');
    }
}