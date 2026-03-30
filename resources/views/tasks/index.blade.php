<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Laravel 12</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen antialiased">
    <div class="max-w-4xl mx-auto py-12 px-4">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Task Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Manage milestones and track progress in real-time.</p>
            </div>
            <div class="text-xs font-medium text-slate-400 uppercase bg-slate-100 px-3 py-1 rounded-md">Laravel 12.x</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <form action="{{ route('tasks.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <input type="text" name="title" required placeholder="What needs to be done?" 
                    class="flex-1 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Create Task
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200 text-left">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Task Details</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $task->title }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                    class="text-xs font-bold rounded-full px-3 py-1.5 cursor-pointer outline-none border-none ring-1 ring-inset
                                    {{ $task->status->value === 'completed' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : ($task->status->value === 'in_progress' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/10') }}">
                                    @foreach(\App\Enums\TaskStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ $task->status === $status ? 'selected' : '' }}>{{ strtoupper($status->name) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 italic text-sm">No tasks found. Start by adding one above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>