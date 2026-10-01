<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Task Manager - Laravel 12</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen antialiased">

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        {{-- ============================================================
        HEADER
    ============================================================= --}}

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center
                sm:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Task Dashboard
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Manage tasks, priorities, statuses, and deadlines.
                </p>

            </div>

            <div class="text-xs font-semibold text-slate-500 uppercase
                    bg-white border border-slate-200
                    px-4 py-2 rounded-lg shadow-sm">

                Laravel 12.x

            </div>

        </div>


        {{-- ============================================================
        SUCCESS MESSAGE
    ============================================================= --}}

        @if(session('success'))

        <div class="mb-6 rounded-lg border border-emerald-200
                    bg-emerald-50 px-4 py-3
                    text-sm font-medium text-emerald-700">

            {{ session('success') }}

        </div>

        @endif


        {{-- ============================================================
        VALIDATION ERRORS
    ============================================================= --}}

        @if($errors->any())

        <div class="mb-6 rounded-lg border border-red-200
                    bg-red-50 px-4 py-3 text-sm text-red-700">

            <div class="font-semibold mb-1">
                Please fix the following errors:
            </div>

            <ul class="list-disc list-inside">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif


        {{-- ============================================================
        STATUS ANALYTICS
    ============================================================= --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5
                gap-4 mb-8">

            {{-- Total --}}
            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">
                    Total Tasks
                </p>

                <p class="mt-2 text-3xl font-extrabold text-slate-900">
                    {{ $totalTasks }}
                </p>

            </div>


            {{-- Pending --}}
            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">
                    Pending
                </p>

                <p class="mt-2 text-3xl font-extrabold text-amber-600">
                    {{ $pendingTasks }}
                </p>

            </div>


            {{-- In Progress --}}
            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">
                    In Progress
                </p>

                <p class="mt-2 text-3xl font-extrabold text-blue-600">
                    {{ $inProgressTasks }}
                </p>

            </div>


            {{-- Completed --}}
            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">
                    Completed
                </p>

                <p class="mt-2 text-3xl font-extrabold text-emerald-600">
                    {{ $completedTasks }}
                </p>

            </div>


            {{-- Completion --}}
            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">
                    Completion
                </p>

                <p class="mt-2 text-3xl font-extrabold text-indigo-600">
                    {{ $completionPercentage }}%
                </p>

            </div>

        </div>


        {{-- ============================================================
        PRIORITY ANALYTICS
    ============================================================= --}}

        <div class="mb-8">

            <div class="flex items-center justify-between mb-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-900">
                        Priority Overview
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Monitor the distribution of task priorities.
                    </p>

                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- High --}}
                <div class="bg-white rounded-xl border border-red-200
                        shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-xs font-semibold text-red-500 uppercase">
                                High Priority
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-red-600">
                                {{ $highPriorityTasks }}
                            </p>

                        </div>

                        <div class="h-11 w-11 rounded-lg bg-red-50
                                flex items-center justify-center">

                            <span class="text-red-600 text-xl font-bold">
                                !
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Medium --}}
                <div class="bg-white rounded-xl border border-orange-200
                        shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-xs font-semibold text-orange-500 uppercase">
                                Medium Priority
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-orange-600">
                                {{ $mediumPriorityTasks }}
                            </p>

                        </div>

                        <div class="h-11 w-11 rounded-lg bg-orange-50
                                flex items-center justify-center">

                            <span class="text-orange-600 text-xl font-bold">
                                =
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Low --}}
                <div class="bg-white rounded-xl border border-emerald-200
                        shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-xs font-semibold text-emerald-500 uppercase">
                                Low Priority
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-emerald-600">
                                {{ $lowPriorityTasks }}
                            </p>

                        </div>

                        <div class="h-11 w-11 rounded-lg bg-emerald-50
                                flex items-center justify-center">

                            <span class="text-emerald-600 text-xl font-bold">
                                ↓
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
        DUE DATE SUMMARY
    ============================================================= --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

            <div class="bg-white rounded-xl border border-red-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-red-500 uppercase">
                    Overdue Tasks
                </p>

                <p class="mt-2 text-2xl font-extrabold text-red-600">
                    {{ $overdueTasks }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Incomplete tasks past their due date
                </p>

            </div>


            <div class="bg-white rounded-xl border border-orange-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-orange-500 uppercase">
                    Due Today
                </p>

                <p class="mt-2 text-2xl font-extrabold text-orange-600">
                    {{ $dueTodayTasks }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Incomplete tasks due today
                </p>

            </div>

        </div>


        {{-- ============================================================
        CREATE TASK
    ============================================================= --}}

        <div class="bg-white rounded-xl shadow-sm border border-slate-200
                p-6 mb-8">

            <div class="mb-5">

                <h2 class="text-lg font-bold text-slate-900">
                    Create New Task
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Add a task with status, priority, and optional due date.
                </p>

            </div>


            <form
                action="{{ route('tasks.store') }}"
                method="POST"
                class="grid grid-cols-1 md:grid-cols-12 gap-4">

                @csrf


                {{-- Title --}}
                <div class="md:col-span-5">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Task Title

                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        maxlength="255"
                        placeholder="What needs to be done?"
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500
                           outline-none transition-all">

                </div>


                {{-- Priority --}}
                <div class="md:col-span-3">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Priority

                    </label>

                    <select
                        name="priority"
                        required
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500
                           outline-none">

                        @foreach(\App\Enums\TaskPriority::cases() as $taskPriority)

                        <option
                            value="{{ $taskPriority->value }}"
                            {{ old('priority', 'medium') === $taskPriority->value
                                ? 'selected'
                                : '' }}>

                            {{ ucfirst($taskPriority->value) }}

                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Due Date --}}
                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Due Date

                    </label>

                    <input
                        type="date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500
                           outline-none transition-all">

                </div>


                {{-- Button --}}
                <div class="md:col-span-2 flex items-end">

                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700
                           text-white font-semibold px-6 py-2.5
                           rounded-lg text-sm shadow-sm
                           transition-all">

                        + Create Task

                    </button>

                </div>

            </form>

        </div>


        {{-- ============================================================
        SEARCH AND FILTER
        ============================================================= --}}

        <div class="bg-white rounded-xl shadow-sm border border-slate-200
                p-6 mb-6">

            <form
                action="{{ route('tasks.index') }}"
                method="GET"
                class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- Search --}}
                <div class="md:col-span-5">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Search Tasks

                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by task title..."
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500
                           outline-none">

                </div>


                {{-- Status --}}
                <div class="md:col-span-3">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Status

                    </label>

                    <select
                        name="status"
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500
                           outline-none">

                        <option value="">
                            All Statuses
                        </option>

                        @foreach(\App\Enums\TaskStatus::cases() as $taskStatus)

                        <option
                            value="{{ $taskStatus->value }}"
                            {{ $status === $taskStatus->value
                                ? 'selected'
                                : '' }}>

                            {{ ucwords(str_replace('_', ' ', $taskStatus->value)) }}

                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Priority --}}
                <div class="md:col-span-3">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Priority

                    </label>

                    <select
                        name="priority"
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500
                           outline-none">

                        <option value="">
                            All Priorities
                        </option>

                        @foreach(\App\Enums\TaskPriority::cases() as $taskPriority)

                        <option
                            value="{{ $taskPriority->value }}"
                            {{ $priority === $taskPriority->value
                                ? 'selected'
                                : '' }}>

                            {{ ucfirst($taskPriority->value) }}

                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Buttons --}}
                <div class="md:col-span-12 flex flex-wrap gap-3">

                    <button
                        type="submit"
                        class="bg-slate-900 hover:bg-slate-800
                           text-white px-5 py-2.5 rounded-lg
                           text-sm font-semibold transition">

                        Search & Filter

                    </button>


                    @if($search || $status || $priority)

                    <a
                        href="{{ route('tasks.index') }}"
                        class="bg-slate-100 hover:bg-slate-200
                               text-slate-700 px-5 py-2.5
                               rounded-lg text-sm font-semibold
                               transition">

                        Clear Filters

                    </a>

                    @endif

                </div>

            </form>

        </div>


        {{-- ============================================================
        TASK TABLE
         ============================================================= --}}

        <div class="bg-white rounded-xl shadow-sm border border-slate-200
                overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200
                    flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-2">

                <div>

                    <h2 class="text-lg font-bold text-slate-900">
                        Task List
                    </h2>

                    <p class="text-xs text-slate-500 mt-1">

                        Manage your tasks by status, priority, and deadline.

                    </p>

                </div>

                <div class="text-xs font-semibold text-slate-500">

                    {{ $tasks->total() }}
                    result{{ $tasks->total() === 1 ? '' : 's' }}

                </div>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200 text-left">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold
                               text-slate-500 uppercase tracking-wider">

                                Task

                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                               text-slate-500 uppercase tracking-wider">

                                Priority

                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                               text-slate-500 uppercase tracking-wider">

                                Status

                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                               text-slate-500 uppercase tracking-wider">

                                Due Date

                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                               text-slate-500 uppercase tracking-wider">

                                Deadline

                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                               text-slate-500 uppercase tracking-wider text-right">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200">

                        @forelse($tasks as $task)

                        @php

                        $isCompleted =
                        $task->status === \App\Enums\TaskStatus::COMPLETED;

                        $isDueToday =
                        $task->due_date &&
                        $task->due_date->isToday() &&
                        !$isCompleted;

                        $isOverdue =
                        $task->due_date &&
                        $task->due_date->startOfDay()->isBefore(now()->startOfDay()) &&
                        !$isCompleted;

                        @endphp

                        <tr class="hover:bg-slate-50/50 transition-colors">

                            {{-- Task --}}
                            <td class="px-6 py-4">

                                <div class="text-sm font-semibold text-slate-700">

                                    {{ $task->title }}

                                </div>

                                <div class="text-xs text-slate-400 mt-1">

                                    Created
                                    {{ $task->created_at->format('d M Y') }}

                                </div>

                            </td>


                            {{-- Priority --}}
                            <td class="px-6 py-4">

                                @if($task->priority === \App\Enums\TaskPriority::HIGH)

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-red-50
                                           px-3 py-1 text-xs
                                           font-bold text-red-700
                                           ring-1 ring-inset
                                           ring-red-600/20">

                                    HIGH

                                </span>

                                @elseif($task->priority === \App\Enums\TaskPriority::MEDIUM)

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-orange-50
                                           px-3 py-1 text-xs
                                           font-bold text-orange-700
                                           ring-1 ring-inset
                                           ring-orange-600/20">

                                    MEDIUM

                                </span>

                                @else

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-emerald-50
                                           px-3 py-1 text-xs
                                           font-bold text-emerald-700
                                           ring-1 ring-inset
                                           ring-emerald-600/20">

                                    LOW

                                </span>

                                @endif

                            </td>



                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <form
                                    action="{{ route('tasks.update', $task->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <select
                                        name="status"
                                        onchange="this.form.submit()"
                                        class="w-36 appearance-auto
                   rounded-lg border border-slate-200
                   bg-white px-3 py-2
                   text-xs font-semibold text-slate-700
                   shadow-sm cursor-pointer
                   outline-none
                   focus:border-indigo-500
                   focus:ring-2 focus:ring-indigo-200">

                                        @foreach(\App\Enums\TaskStatus::cases() as $taskStatus)

                                        <option
                                            value="{{ $taskStatus->value }}"
                                            @selected($task->status === $taskStatus)
                                            >
                                            {{ strtoupper(str_replace('_', ' ', $taskStatus->value)) }}
                                        </option>

                                        @endforeach

                                    </select>

                                </form>

                            </td>

                            {{-- Due Date --}}
                            <td class="px-6 py-4">

                                @if($task->due_date)

                                <div class="text-sm font-medium text-slate-700">

                                    {{ $task->due_date->format('d M Y') }}

                                </div>

                                <div class="text-xs text-slate-400 mt-1">

                                    {{ $task->due_date->format('l') }}

                                </div>

                                @else

                                <span class="text-xs text-slate-400 italic">
                                    No due date
                                </span>

                                @endif

                            </td>


                            {{-- Deadline --}}
                            <td class="px-6 py-4">

                                @if($task->status === \App\Enums\TaskStatus::COMPLETED)

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-emerald-50
                                           px-3 py-1 text-xs
                                           font-semibold text-emerald-700">

                                    Completed

                                </span>

                                @elseif($isOverdue)

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-red-50
                                           px-3 py-1 text-xs
                                           font-semibold text-red-700">

                                    Overdue

                                </span>

                                @elseif($isDueToday)

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-orange-50
                                           px-3 py-1 text-xs
                                           font-semibold text-orange-700">

                                    Due Today

                                </span>

                                @elseif($task->due_date)

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-blue-50
                                           px-3 py-1 text-xs
                                           font-semibold text-blue-700">

                                    Upcoming

                                </span>

                                @else

                                <span
                                    class="inline-flex items-center
                                           rounded-full bg-slate-100
                                           px-3 py-1 text-xs
                                           font-semibold text-slate-500">

                                    No Deadline

                                </span>

                                @endif

                            </td>


                            {{-- Delete --}}
                            <td class="px-6 py-4 text-right">

                                <form
                                    action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                    'Are you sure you want to delete this task?'
                                )">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-slate-400
                                           hover:text-red-600
                                           transition-colors"
                                        title="Delete Task">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 inline"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                                        </svg>

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-16 text-center">

                                <div class="text-slate-400">

                                    <p class="font-medium">
                                        No tasks found.
                                    </p>

                                    <p class="text-sm mt-1">
                                        Try creating a task or changing your filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ============================================================
            PAGINATION
        ============================================================= --}}

            @if($tasks->hasPages())

            <div class="px-6 py-4 border-t border-slate-200">

                {{ $tasks->links() }}

            </div>

            @endif

        </div>


        {{-- Footer --}}
        <div class="mt-6 text-center text-xs text-slate-400">

            Laravel 12 • PHP Enum • Eloquent Enum Casting •
            Task Status • Priority • Analytics

        </div>

    </div>

</body>

</html>