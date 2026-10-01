<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display the task dashboard.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $priority = $request->input('priority');

        /*
        |--------------------------------------------------------------------------
        | Task Query
        |--------------------------------------------------------------------------
        */

        $query = Task::query();

        // Search by task title
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Filter by status Enum
        if (
            $status &&
            in_array($status, array_column(TaskStatus::cases(), 'value'))
        ) {
            $query->where('status', $status);
        }

        // Filter by priority Enum
        if (
            $priority &&
            in_array($priority, array_column(TaskPriority::cases(), 'value'))
        ) {
            $query->where('priority', $priority);
        }

        /*
        |--------------------------------------------------------------------------
        | Task Ordering
        |--------------------------------------------------------------------------
        |
        | High priority tasks appear first.
        | Within the same priority, tasks with earlier due dates appear first.
        |
        */

        $tasks = $query
            ->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                    ELSE 4
                END
            ")
            ->orderByRaw("
                CASE
                    WHEN due_date IS NULL THEN 1
                    ELSE 0
                END
            ")
            ->orderBy('due_date', 'asc')
            ->latest('id')
            ->paginate(8)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Status Analytics
        |--------------------------------------------------------------------------
        */

        $totalTasks = Task::count();

        $pendingTasks = Task::where(
            'status',
            TaskStatus::PENDING
        )->count();

        $inProgressTasks = Task::where(
            'status',
            TaskStatus::IN_PROGRESS
        )->count();

        $completedTasks = Task::where(
            'status',
            TaskStatus::COMPLETED
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Priority Analytics
        |--------------------------------------------------------------------------
        */

        $highPriorityTasks = Task::where(
            'priority',
            TaskPriority::HIGH
        )->count();

        $mediumPriorityTasks = Task::where(
            'priority',
            TaskPriority::MEDIUM
        )->count();

        $lowPriorityTasks = Task::where(
            'priority',
            TaskPriority::LOW
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Due Date Analytics
        |--------------------------------------------------------------------------
        */

        $today = now()->startOfDay();

        $overdueTasks = Task::whereDate('due_date', '<', $today)
            ->where('status', '!=', TaskStatus::COMPLETED)
            ->count();

        $dueTodayTasks = Task::whereDate('due_date', $today)
            ->where('status', '!=', TaskStatus::COMPLETED)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Completion Percentage
        |--------------------------------------------------------------------------
        */

        $completionPercentage = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        return view('tasks.index', compact(
            'tasks',
            'search',
            'status',
            'priority',
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'highPriorityTasks',
            'mediumPriorityTasks',
            'lowPriorityTasks',
            'overdueTasks',
            'dueTodayTasks',
            'completionPercentage'
        ));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'priority' => [
                'required',
                Rule::enum(TaskPriority::class),
            ],
        ]);

        Task::create([
            'title' => $validated['title'],
            'status' => TaskStatus::PENDING,
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Update task status.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::enum(TaskStatus::class),
            ],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task status updated successfully!');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}