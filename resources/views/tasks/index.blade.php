<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Task Manager - Laravel 12</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .edit-row {
            display: none;
        }

        .edit-row.show {
            display: table-row;
        }
    </style>

</head>


<body class="bg-slate-50 min-h-screen antialiased">


    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">


        {{-- =========================================================
        HEADER
    ========================================================== --}}

        <div class="mb-8 flex flex-col sm:flex-row
                sm:items-center sm:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-extrabold text-slate-900">

                    Task Dashboard

                </h1>

                <p class="mt-1 text-sm text-slate-500">

                    Manage tasks, priorities, statuses, deadlines,
                    bulk actions and exports.

                </p>

            </div>


            <div class="text-xs font-semibold text-slate-500
                    uppercase bg-white border border-slate-200
                    px-4 py-2 rounded-lg shadow-sm">

                Laravel 12.x

            </div>

        </div>


        {{-- =========================================================
        SUCCESS
    ========================================================== --}}

        @if(session('success'))

        <div class="mb-6 rounded-lg border border-emerald-200
                    bg-emerald-50 px-4 py-3
                    text-sm font-medium text-emerald-700">

            {{ session('success') }}

        </div>

        @endif


        {{-- =========================================================
        ERROR
    ========================================================== --}}

        @if(session('error'))

        <div class="mb-6 rounded-lg border border-red-200
                    bg-red-50 px-4 py-3
                    text-sm font-medium text-red-700">

            {{ session('error') }}

        </div>

        @endif


        {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

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


        {{-- =========================================================
        STATUS ANALYTICS
    ========================================================== --}}

        <div class="grid grid-cols-1 sm:grid-cols-2
                lg:grid-cols-5 gap-4 mb-8">


            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">

                    Total Tasks

                </p>

                <p class="mt-2 text-3xl font-extrabold text-slate-900">

                    {{ $totalTasks }}

                </p>

            </div>


            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">

                    Pending

                </p>

                <p class="mt-2 text-3xl font-extrabold text-amber-600">

                    {{ $pendingTasks }}

                </p>

            </div>


            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">

                    In Progress

                </p>

                <p class="mt-2 text-3xl font-extrabold text-blue-600">

                    {{ $inProgressTasks }}

                </p>

            </div>


            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-slate-500 uppercase">

                    Completed

                </p>

                <p class="mt-2 text-3xl font-extrabold text-emerald-600">

                    {{ $completedTasks }}

                </p>

            </div>


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


        {{-- =========================================================
        PRIORITY ANALYTICS
    ========================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">


            <div class="bg-white rounded-xl border border-red-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-red-500 uppercase">

                    High Priority

                </p>

                <p class="mt-2 text-3xl font-extrabold text-red-600">

                    {{ $highPriorityTasks }}

                </p>

            </div>


            <div class="bg-white rounded-xl border border-orange-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-orange-500 uppercase">

                    Medium Priority

                </p>

                <p class="mt-2 text-3xl font-extrabold text-orange-600">

                    {{ $mediumPriorityTasks }}

                </p>

            </div>


            <div class="bg-white rounded-xl border border-emerald-200
                    shadow-sm p-5">

                <p class="text-xs font-semibold text-emerald-500 uppercase">

                    Low Priority

                </p>

                <p class="mt-2 text-3xl font-extrabold text-emerald-600">

                    {{ $lowPriorityTasks }}

                </p>

            </div>

        </div>


        {{-- =========================================================
        DEADLINE ANALYTICS
    ========================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">


            <a
                href="{{ route('tasks.index', ['deadline' => 'overdue']) }}"
                class="bg-white rounded-xl border border-red-200
                   shadow-sm p-5 hover:bg-red-50 transition">

                <p class="text-xs font-semibold text-red-500 uppercase">

                    Overdue

                </p>

                <p class="mt-2 text-3xl font-extrabold text-red-600">

                    {{ $overdueTasks }}

                </p>

                <p class="text-xs text-slate-500 mt-1">

                    View overdue tasks

                </p>

            </a>


            <a
                href="{{ route('tasks.index', ['deadline' => 'today']) }}"
                class="bg-white rounded-xl border border-orange-200
                   shadow-sm p-5 hover:bg-orange-50 transition">

                <p class="text-xs font-semibold text-orange-500 uppercase">

                    Due Today

                </p>

                <p class="mt-2 text-3xl font-extrabold text-orange-600">

                    {{ $dueTodayTasks }}

                </p>

                <p class="text-xs text-slate-500 mt-1">

                    View today's tasks

                </p>

            </a>


            <a
                href="{{ route('tasks.index', ['deadline' => 'upcoming']) }}"
                class="bg-white rounded-xl border border-blue-200
                   shadow-sm p-5 hover:bg-blue-50 transition">

                <p class="text-xs font-semibold text-blue-500 uppercase">

                    Upcoming

                </p>

                <p class="mt-2 text-3xl font-extrabold text-blue-600">

                    {{ $upcomingTasks }}

                </p>

                <p class="text-xs text-slate-500 mt-1">

                    View upcoming tasks

                </p>

            </a>

        </div>


        {{-- =========================================================
        CREATE TASK
    ========================================================== --}}

        <div class="bg-white rounded-xl shadow-sm
                border border-slate-200 p-6 mb-8">

            <h2 class="text-lg font-bold text-slate-900">

                Create New Task

            </h2>

            <p class="text-sm text-slate-500 mt-1 mb-5">

                Add a task with priority and optional due date.

            </p>


            <form
                action="{{ route('tasks.store') }}"
                method="POST"
                class="grid grid-cols-1 md:grid-cols-12 gap-4">

                @csrf


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
                           outline-none">

                </div>


                <div class="md:col-span-3">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Priority

                    </label>

                    <select
                        name="priority"
                        required
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm">

                        @foreach(\App\Enums\TaskPriority::cases() as $taskPriority)

                        <option
                            value="{{ $taskPriority->value }}"
                            @selected(
                            old('priority', 'medium' )===$taskPriority->value
                            )>

                            {{ ucfirst($taskPriority->value) }}

                        </option>

                        @endforeach

                    </select>

                </div>


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
                           rounded-lg px-4 py-2.5 text-sm">

                </div>


                <div class="md:col-span-2 flex items-end">

                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700
                           text-white font-semibold px-6 py-2.5
                           rounded-lg text-sm">

                        + Create Task

                    </button>

                </div>

            </form>

        </div>


        {{-- =========================================================
        SEARCH + FILTER
    ========================================================== --}}

        <div class="bg-white rounded-xl shadow-sm
                border border-slate-200 p-6 mb-6">

            <form
                action="{{ route('tasks.index') }}"
                method="GET"
                class="grid grid-cols-1 md:grid-cols-12 gap-4">


                <div class="md:col-span-4">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Search

                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search task title..."
                        class="w-full border border-slate-200
                           rounded-lg px-4 py-2.5 text-sm">

                </div>


                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Status

                    </label>

                    <select
                        name="status"
                        class="w-full border border-slate-200
                           rounded-lg px-3 py-2.5 text-sm">

                        <option value="">
                            All
                        </option>

                        @foreach(\App\Enums\TaskStatus::cases() as $taskStatus)

                        <option
                            value="{{ $taskStatus->value }}"
                            @selected($status===$taskStatus->value)>

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $taskStatus->value
                                )
                            ) }}

                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Priority

                    </label>

                    <select
                        name="priority"
                        class="w-full border border-slate-200
                           rounded-lg px-3 py-2.5 text-sm">

                        <option value="">
                            All
                        </option>

                        @foreach(\App\Enums\TaskPriority::cases() as $taskPriority)

                        <option
                            value="{{ $taskPriority->value }}"
                            @selected($priority===$taskPriority->value)>

                            {{ ucfirst($taskPriority->value) }}

                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Due From

                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                        class="w-full border border-slate-200
                           rounded-lg px-3 py-2.5 text-sm">

                </div>


                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold
                              text-slate-600 mb-2">

                        Due To

                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                        class="w-full border border-slate-200
                           rounded-lg px-3 py-2.5 text-sm">

                </div>


                <div class="md:col-span-12 flex flex-wrap gap-3">


                    <button
                        type="submit"
                        class="bg-slate-900 hover:bg-slate-800
                           text-white px-5 py-2.5 rounded-lg
                           text-sm font-semibold">

                        Search & Filter

                    </button>


                    <a
                        href="{{ route('tasks.index') }}"
                        class="bg-slate-100 hover:bg-slate-200
                           text-slate-700 px-5 py-2.5 rounded-lg
                           text-sm font-semibold">

                        Clear

                    </a>


                    <a
                        href="{{ route('tasks.index', ['deadline' => 'overdue']) }}"
                        class="bg-red-50 hover:bg-red-100
                           text-red-700 px-5 py-2.5 rounded-lg
                           text-sm font-semibold">

                        Overdue

                    </a>


                    <a
                        href="{{ route('tasks.index', ['deadline' => 'today']) }}"
                        class="bg-orange-50 hover:bg-orange-100
                           text-orange-700 px-5 py-2.5 rounded-lg
                           text-sm font-semibold">

                        Today

                    </a>


                    <a
                        href="{{ route('tasks.index', ['deadline' => 'upcoming']) }}"
                        class="bg-blue-50 hover:bg-blue-100
                           text-blue-700 px-5 py-2.5 rounded-lg
                           text-sm font-semibold">

                        Upcoming

                    </a>


                    <a
                        href="{{ route('tasks.export', request()->query()) }}"
                        class="bg-emerald-600 hover:bg-emerald-700
                           text-white px-5 py-2.5 rounded-lg
                           text-sm font-semibold">

                        Export CSV

                    </a>

                </div>

            </form>

        </div>


        {{-- =========================================================
        SORT
    ========================================================== --}}

        <div class="bg-white rounded-xl border border-slate-200
                p-4 mb-6 flex flex-wrap items-center gap-3">

            <span class="text-sm font-semibold text-slate-600">

                Sort:

            </span>


            <a
                href="{{ route('tasks.index', array_merge(
                request()->query(),
                ['sort' => 'created_at', 'direction' => 'desc']
            )) }}"
                class="px-4 py-2 rounded-lg text-xs font-semibold
                   bg-slate-100 hover:bg-slate-200">

                Newest

            </a>


            <a
                href="{{ route('tasks.index', array_merge(
                request()->query(),
                ['sort' => 'title', 'direction' => 'asc']
            )) }}"
                class="px-4 py-2 rounded-lg text-xs font-semibold
                   bg-slate-100 hover:bg-slate-200">

                A-Z

            </a>


            <a
                href="{{ route('tasks.index', array_merge(
                request()->query(),
                ['sort' => 'due_date', 'direction' => 'asc']
            )) }}"
                class="px-4 py-2 rounded-lg text-xs font-semibold
                   bg-slate-100 hover:bg-slate-200">

                Due Date

            </a>


            <a
                href="{{ route('tasks.index', array_merge(
                request()->query(),
                ['sort' => 'priority_high']
            )) }}"
                class="px-4 py-2 rounded-lg text-xs font-semibold
                   bg-red-50 text-red-700 hover:bg-red-100">

                High Priority

            </a>

        </div>


        {{-- =========================================================
        BULK ACTION FORM
    ========================================================== --}}

        <form
            action="{{ route('tasks.bulk') }}"
            method="POST"
            id="bulkForm">

            @csrf


            <div class="bg-white rounded-xl border border-slate-200
                    shadow-sm overflow-hidden">


                {{-- TABLE HEADER --}}

                <div class="px-6 py-5 border-b border-slate-200
                        flex flex-col lg:flex-row
                        lg:items-center lg:justify-between gap-4">

                    <div>

                        <h2 class="text-lg font-bold text-slate-900">

                            Task List

                        </h2>

                        <p class="text-xs text-slate-500 mt-1">

                            {{ $tasks->total() }}
                            result{{ $tasks->total() === 1 ? '' : 's' }}

                        </p>

                    </div>


                    {{-- BULK ACTIONS --}}

                    <div class="flex flex-wrap gap-2">


                        <select
                            name="action"
                            id="bulkAction"
                            class="border border-slate-200
                               rounded-lg px-3 py-2
                               text-sm">

                            <option value="">
                                Bulk Action
                            </option>

                            <option value="status_pending">
                                Mark Pending
                            </option>

                            <option value="status_in_progress">
                                Mark In Progress
                            </option>

                            <option value="status_completed">
                                Mark Completed
                            </option>

                            <option value="priority_low">
                                Priority: Low
                            </option>

                            <option value="priority_medium">
                                Priority: Medium
                            </option>

                            <option value="priority_high">
                                Priority: High
                            </option>

                            <option value="delete">
                                Delete Selected
                            </option>

                        </select>


                        <button
                            type="submit"
                            onclick="return confirmBulkAction()"
                            class="bg-slate-900 hover:bg-slate-800
                               text-white px-4 py-2
                               rounded-lg text-sm font-semibold">

                            Apply

                        </button>

                    </div>

                </div>


                {{-- =================================================
                 TABLE
            ================================================== --}}

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-200">


                        <thead class="bg-slate-50">

                            <tr>


                                <th class="px-4 py-4">

                                    <input
                                        type="checkbox"
                                        id="selectAll"
                                        class="h-4 w-4 rounded">

                                </th>


                                <th class="px-6 py-4 text-left text-xs
                                       font-semibold text-slate-500
                                       uppercase">

                                    Task

                                </th>


                                <th class="px-6 py-4 text-left text-xs
                                       font-semibold text-slate-500
                                       uppercase">

                                    Priority

                                </th>


                                <th class="px-6 py-4 text-left text-xs
                                       font-semibold text-slate-500
                                       uppercase">

                                    Status

                                </th>


                                <th class="px-6 py-4 text-left text-xs
                                       font-semibold text-slate-500
                                       uppercase">

                                    Due Date

                                </th>


                                <th class="px-6 py-4 text-left text-xs
                                       font-semibold text-slate-500
                                       uppercase">

                                    Deadline

                                </th>


                                <th class="px-6 py-4 text-right text-xs
                                       font-semibold text-slate-500
                                       uppercase">

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
                            $task->due_date->startOfDay()
                            ->isBefore(now()->startOfDay()) &&
                            !$isCompleted;

                            @endphp


                            {{-- =================================================
                             MAIN ROW
                        ================================================== --}}

                            <tr class="hover:bg-slate-50 transition">


                                {{-- Checkbox --}}

                                <td class="px-4 py-4">

                                    <input
                                        type="checkbox"
                                        name="task_ids[]"
                                        value="{{ $task->id }}"
                                        class="task-checkbox h-4 w-4 rounded">

                                </td>


                                {{-- Task --}}

                                <td class="px-6 py-4">

                                    <div class="text-sm font-semibold
                                            text-slate-700">

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

                                    <span class="inline-flex rounded-full
                                                 bg-red-50 px-3 py-1
                                                 text-xs font-bold
                                                 text-red-700">

                                        HIGH

                                    </span>

                                    @elseif($task->priority === \App\Enums\TaskPriority::MEDIUM)

                                    <span class="inline-flex rounded-full
                                                 bg-orange-50 px-3 py-1
                                                 text-xs font-bold
                                                 text-orange-700">

                                        MEDIUM

                                    </span>

                                    @else

                                    <span class="inline-flex rounded-full
                                                 bg-emerald-50 px-3 py-1
                                                 text-xs font-bold
                                                 text-emerald-700">

                                        LOW

                                    </span>

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td class="px-6 py-4">

                                    <select
                                        onchange="updateStatus(
                                        {{ $task->id }},
                                        this.value
                                    )"
                                        class="w-36 rounded-lg
                                           border border-slate-200
                                           bg-white px-3 py-2
                                           text-xs font-semibold">

                                        @foreach(\App\Enums\TaskStatus::cases() as $taskStatus)

                                        <option
                                            value="{{ $taskStatus->value }}"
                                            @selected(
                                            $task->status === $taskStatus
                                            )>

                                            {{ strtoupper(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $taskStatus->value
                                                )
                                            ) }}

                                        </option>

                                        @endforeach

                                    </select>


                                    <form
                                        id="status-form-{{ $task->id }}"
                                        action="{{ route(
                                        'tasks.update',
                                        $task
                                    ) }}"
                                        method="POST"
                                        class="hidden">

                                        @csrf

                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="title"
                                            value="{{ $task->title }}">

                                        <input
                                            type="hidden"
                                            name="status"
                                            id="status-input-{{ $task->id }}">

                                        <input
                                            type="hidden"
                                            name="priority"
                                            value="{{ $task->priority->value }}">

                                        <input
                                            type="hidden"
                                            name="due_date"
                                            value="{{ $task->due_date?->format('Y-m-d') }}">

                                    </form>

                                </td>


                                {{-- Due Date --}}

                                <td class="px-6 py-4">

                                    @if($task->due_date)

                                    <div class="text-sm font-medium
                                                text-slate-700">

                                        {{ $task->due_date->format('d M Y') }}

                                    </div>

                                    <div class="text-xs text-slate-400">

                                        {{ $task->due_date->format('l') }}

                                    </div>

                                    @else

                                    <span class="text-xs text-slate-400">

                                        No due date

                                    </span>

                                    @endif

                                </td>


                                {{-- Deadline --}}

                                <td class="px-6 py-4">

                                    @if($isCompleted)

                                    <span class="rounded-full bg-emerald-50
                                                 px-3 py-1 text-xs
                                                 font-semibold
                                                 text-emerald-700">

                                        Completed

                                    </span>

                                    @elseif($isOverdue)

                                    <span class="rounded-full bg-red-50
                                                 px-3 py-1 text-xs
                                                 font-semibold
                                                 text-red-700">

                                        Overdue

                                    </span>

                                    @elseif($isDueToday)

                                    <span class="rounded-full bg-orange-50
                                                 px-3 py-1 text-xs
                                                 font-semibold
                                                 text-orange-700">

                                        Due Today

                                    </span>

                                    @elseif($task->due_date)

                                    <span class="rounded-full bg-blue-50
                                                 px-3 py-1 text-xs
                                                 font-semibold
                                                 text-blue-700">

                                        Upcoming

                                    </span>

                                    @else

                                    <span class="rounded-full bg-slate-100
                                                 px-3 py-1 text-xs
                                                 font-semibold
                                                 text-slate-500">

                                        No Deadline

                                    </span>

                                    @endif

                                </td>


                                {{-- Actions --}}

                                <td class="px-6 py-4 text-right">

                                    <div class="flex justify-end gap-2">


                                        {{-- Edit --}}

                                        <button
                                            type="button"
                                            onclick="toggleEdit(
                                            {{ $task->id }}
                                        )"
                                            class="text-indigo-600
                                               hover:text-indigo-800
                                               text-xs font-semibold">

                                            Edit

                                        </button>


                                        {{-- Duplicate --}}

                                        <form
                                            action="{{ route(
                                            'tasks.duplicate',
                                            $task
                                        ) }}"
                                            method="POST">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-blue-600
                                                   hover:text-blue-800
                                                   text-xs font-semibold">

                                                Duplicate

                                            </button>

                                        </form>


                                        {{-- Delete --}}

                                        <form
                                            action="{{ route(
                                            'tasks.destroy',
                                            $task
                                        ) }}"
                                            method="POST"
                                            onsubmit="
                                            return confirm(
                                                'Delete this task?'
                                            )
                                        ">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-500
                                                   hover:text-red-700
                                                   text-xs font-semibold">

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- =================================================
                             EDIT ROW
                        ================================================== --}}

                            <tr
                                id="edit-row-{{ $task->id }}"
                                class="edit-row bg-indigo-50">

                                <td colspan="7" class="px-6 py-5">

                                    <form
                                        action="{{ route(
                                        'tasks.update',
                                        $task
                                    ) }}"
                                        method="POST"
                                        class="grid grid-cols-1 md:grid-cols-5
                                           gap-4">

                                        @csrf

                                        @method('PATCH')


                                        <div>

                                            <label class="block text-xs
                                                      font-semibold
                                                      text-slate-600 mb-2">

                                                Title

                                            </label>

                                            <input
                                                type="text"
                                                name="title"
                                                value="{{ $task->title }}"
                                                required
                                                class="w-full border
                                                   border-slate-200
                                                   rounded-lg px-3 py-2
                                                   text-sm">

                                        </div>


                                        <div>

                                            <label class="block text-xs
                                                      font-semibold
                                                      text-slate-600 mb-2">

                                                Status

                                            </label>

                                            <select
                                                name="status"
                                                class="w-full border
                                                   border-slate-200
                                                   rounded-lg px-3 py-2
                                                   text-sm">

                                                @foreach(
                                                \App\Enums\TaskStatus::cases()
                                                as $taskStatus
                                                )

                                                <option
                                                    value="{{ $taskStatus->value }}"
                                                    @selected(
                                                    $task->status ===
                                                    $taskStatus
                                                    )>

                                                    {{ ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $taskStatus->value
                                                        )
                                                    ) }}

                                                </option>

                                                @endforeach

                                            </select>

                                        </div>


                                        <div>

                                            <label class="block text-xs
                                                      font-semibold
                                                      text-slate-600 mb-2">

                                                Priority

                                            </label>

                                            <select
                                                name="priority"
                                                class="w-full border
                                                   border-slate-200
                                                   rounded-lg px-3 py-2
                                                   text-sm">

                                                @foreach(
                                                \App\Enums\TaskPriority::cases()
                                                as $taskPriority
                                                )

                                                <option
                                                    value="{{ $taskPriority->value }}"
                                                    @selected(
                                                    $task->priority ===
                                                    $taskPriority
                                                    )>

                                                    {{ ucfirst(
                                                        $taskPriority->value
                                                    ) }}

                                                </option>

                                                @endforeach

                                            </select>

                                        </div>


                                        <div>

                                            <label class="block text-xs
                                                      font-semibold
                                                      text-slate-600 mb-2">

                                                Due Date

                                            </label>

                                            <input
                                                type="date"
                                                name="due_date"
                                                value="{{ $task->due_date?->format('Y-m-d') }}"
                                                class="w-full border
                                                   border-slate-200
                                                   rounded-lg px-3 py-2
                                                   text-sm">

                                        </div>


                                        <div class="flex items-end gap-2">

                                            <button
                                                type="submit"
                                                class="bg-indigo-600
                                                   hover:bg-indigo-700
                                                   text-white px-4 py-2
                                                   rounded-lg text-sm
                                                   font-semibold">

                                                Save

                                            </button>


                                            <button
                                                type="button"
                                                onclick="toggleEdit(
                                                {{ $task->id }}
                                            )"
                                                class="bg-white
                                                   border border-slate-200
                                                   px-4 py-2 rounded-lg
                                                   text-sm font-semibold">

                                                Cancel

                                            </button>

                                        </div>

                                    </form>

                                </td>

                            </tr>


                            @empty


                            <tr>

                                <td
                                    colspan="7"
                                    class="px-6 py-16 text-center">

                                    <p class="font-medium text-slate-500">

                                        No tasks found.

                                    </p>

                                    <p class="text-sm text-slate-400 mt-1">

                                        Try changing your filters.

                                    </p>

                                </td>

                            </tr>


                            @endforelse


                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                 PAGINATION
            ================================================== --}}

                @if($tasks->hasPages())

                <div class="px-6 py-4 border-t border-slate-200">

                    {{ $tasks->links() }}

                </div>

                @endif


            </div>

        </form>


        {{-- =========================================================
        FOOTER
    ========================================================== --}}

        <div class="mt-6 text-center text-xs text-slate-400">

            Laravel 12 • PHP Enum • Eloquent Enum Casting •
            Search • Filters • Priority • Bulk Actions • CSV Export

        </div>


    </div>


    {{-- =============================================================
     JAVASCRIPT
============================================================== --}}

    <script>
        /*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/

        document
            .getElementById('selectAll')
            .addEventListener('change', function() {

                document
                    .querySelectorAll('.task-checkbox')
                    .forEach(function(checkbox) {

                        checkbox.checked = this.checked;

                    }, this);

            });


        /*
        |--------------------------------------------------------------------------
        | Bulk Action Confirmation
        |--------------------------------------------------------------------------
        */

        function confirmBulkAction() {
            const checkboxes =
                document.querySelectorAll(
                    '.task-checkbox:checked'
                );

            const action =
                document.getElementById('bulkAction').value;


            if (checkboxes.length === 0) {

                alert('Please select at least one task.');

                return false;

            }


            if (!action) {

                alert('Please select a bulk action.');

                return false;

            }


            if (action === 'delete') {

                return confirm(
                    'Are you sure you want to delete the selected tasks?'
                );

            }


            return confirm(
                'Apply this action to the selected tasks?'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Edit Row
        |--------------------------------------------------------------------------
        */

        function toggleEdit(id) {
            const row =
                document.getElementById(
                    'edit-row-' + id
                );

            row.classList.toggle('show');
        }


        /*
        |--------------------------------------------------------------------------
        | Quick Status Update
        |--------------------------------------------------------------------------
        */

        function updateStatus(id, status) {
            document.getElementById(
                'status-input-' + id
            ).value = status;


            document.getElementById(
                'status-form-' + id
            ).submit();
        }
    </script>


</body>

</html>