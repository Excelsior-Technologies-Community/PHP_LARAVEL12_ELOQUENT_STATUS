<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TaskController extends Controller
{
    /**
     * Display task dashboard.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $priority = $request->input('priority');

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $deadline = $request->input('deadline');

        $sort = $request->input('sort', 'latest');
        $direction = $request->input('direction', 'desc');

        /*
        |--------------------------------------------------------------------------
        | Task Query
        |--------------------------------------------------------------------------
        */

        $query = Task::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $status &&
            in_array($status, array_column(TaskStatus::cases(), 'value'))
        ) {
            $query->where('status', $status);
        }

        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if (
            $priority &&
            in_array($priority, array_column(TaskPriority::cases(), 'value'))
        ) {
            $query->where('priority', $priority);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range Filter
        |--------------------------------------------------------------------------
        */

        if ($dateFrom) {
            $query->whereDate('due_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('due_date', '<=', $dateTo);
        }

        /*
        |--------------------------------------------------------------------------
        | Quick Deadline Filter
        |--------------------------------------------------------------------------
        */

        if ($deadline === 'overdue') {
            $query->whereDate('due_date', '<', today())
                ->where('status', '!=', TaskStatus::COMPLETED);
        }

        if ($deadline === 'today') {
            $query->whereDate('due_date', today())
                ->where('status', '!=', TaskStatus::COMPLETED);
        }

        if ($deadline === 'upcoming') {
            $query->whereDate('due_date', '>', today())
                ->where('status', '!=', TaskStatus::COMPLETED);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'title',
            'priority',
            'status',
            'due_date',
            'created_at',
        ];

        if (in_array($sort, $allowedSorts)) {

            $direction = $direction === 'asc'
                ? 'asc'
                : 'desc';

            $query->orderBy($sort, $direction);

        } elseif ($sort === 'priority_high') {

            $query->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                    ELSE 4
                END
            ");

            $query->orderBy('due_date', 'asc');

        } else {

            $query->latest('id');

        }

        $tasks = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Analytics
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
        | Deadline Analytics
        |--------------------------------------------------------------------------
        */

        $overdueTasks = Task::whereDate('due_date', '<', today())
            ->where('status', '!=', TaskStatus::COMPLETED)
            ->count();

        $dueTodayTasks = Task::whereDate('due_date', today())
            ->where('status', '!=', TaskStatus::COMPLETED)
            ->count();

        $upcomingTasks = Task::whereDate('due_date', '>', today())
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
            'dateFrom',
            'dateTo',
            'deadline',
            'sort',
            'direction',
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'highPriorityTasks',
            'mediumPriorityTasks',
            'lowPriorityTasks',
            'overdueTasks',
            'dueTodayTasks',
            'upcomingTasks',
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
     * Update task.
     *
     * Supports:
     * - Title
     * - Status
     * - Priority
     * - Due Date
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::enum(TaskStatus::class),
            ],

            'priority' => [
                'required',
                Rule::enum(TaskPriority::class),
            ],

            'due_date' => [
                'nullable',
                'date',
            ],
        ]);

        $task->update([
            'title' => $validated['title'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }


    /**
     * Duplicate task.
     */
    public function duplicate(Task $task)
    {
        $newTask = $task->replicate();

        $newTask->title = $task->title . ' (Copy)';

        $newTask->status = TaskStatus::PENDING;

        $newTask->save();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task duplicated successfully!');
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


    /**
     * Bulk actions.
     *
     * Actions:
     * - delete
     * - status_pending
     * - status_in_progress
     * - status_completed
     * - priority_low
     * - priority_medium
     * - priority_high
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'task_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'task_ids.*' => [
                'integer',
                'exists:tasks,id',
            ],

            'action' => [
                'required',
                'string',
            ],
        ]);

        $taskIds = $validated['task_ids'];
        $action = $validated['action'];

        $tasks = Task::whereIn('id', $taskIds);

        switch ($action) {

            case 'delete':

                $tasks->delete();

                $message = count($taskIds) . ' task(s) deleted successfully.';

                break;


            case 'status_pending':

                $tasks->update([
                    'status' => TaskStatus::PENDING,
                ]);

                $message = 'Selected tasks marked as pending.';

                break;


            case 'status_in_progress':

                $tasks->update([
                    'status' => TaskStatus::IN_PROGRESS,
                ]);

                $message = 'Selected tasks marked as in progress.';

                break;


            case 'status_completed':

                $tasks->update([
                    'status' => TaskStatus::COMPLETED,
                ]);

                $message = 'Selected tasks marked as completed.';

                break;


            case 'priority_low':

                $tasks->update([
                    'priority' => TaskPriority::LOW,
                ]);

                $message = 'Selected tasks changed to low priority.';

                break;


            case 'priority_medium':

                $tasks->update([
                    'priority' => TaskPriority::MEDIUM,
                ]);

                $message = 'Selected tasks changed to medium priority.';

                break;


            case 'priority_high':

                $tasks->update([
                    'priority' => TaskPriority::HIGH,
                ]);

                $message = 'Selected tasks changed to high priority.';

                break;


            default:

                return redirect()
                    ->route('tasks.index')
                    ->with('error', 'Invalid bulk action.');
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', $message);
    }


    /**
     * Export tasks to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $priority = $request->input('priority');

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $deadline = $request->input('deadline');

        $query = Task::query();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if (
            $status &&
            in_array($status, array_column(TaskStatus::cases(), 'value'))
        ) {
            $query->where('status', $status);
        }

        if (
            $priority &&
            in_array($priority, array_column(TaskPriority::cases(), 'value'))
        ) {
            $query->where('priority', $priority);
        }

        if ($dateFrom) {
            $query->whereDate('due_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('due_date', '<=', $dateTo);
        }

        if ($deadline === 'overdue') {
            $query->whereDate('due_date', '<', today())
                ->where('status', '!=', TaskStatus::COMPLETED);
        }

        if ($deadline === 'today') {
            $query->whereDate('due_date', today())
                ->where('status', '!=', TaskStatus::COMPLETED);
        }

        if ($deadline === 'upcoming') {
            $query->whereDate('due_date', '>', today())
                ->where('status', '!=', TaskStatus::COMPLETED);
        }

        $tasks = $query
            ->latest('id')
            ->get();

        $fileName = 'tasks-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($tasks) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Title',
                'Status',
                'Priority',
                'Due Date',
                'Created At',
            ]);

            foreach ($tasks as $task) {

                fputcsv($handle, [
                    $task->id,
                    $task->title,
                    $task->status->value,
                    $task->priority->value,
                    $task->due_date?->format('Y-m-d'),
                    $task->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);

        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}