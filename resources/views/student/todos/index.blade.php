<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">My Todos</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="card p-4">
                <form method="POST" action="{{ route('todos.store') }}">
                    @csrf
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="title" placeholder="What needs to be done?" required class="input text-sm">
                        </div>
                        <div>
                            <select name="priority" class="input text-sm">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="category" placeholder="Category" class="input text-sm">
                        </div>
                        <div>
                            <input type="date" name="due_date" class="input text-sm">
                        </div>
                        <button type="submit" class="btn-primary text-sm">Add Task</button>
                    </div>
                </form>
            </div>

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <select name="category" class="input text-sm">
                            <option value="">All categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="priority" class="input text-sm">
                            <option value="">All priorities</option>
                            <option value="low" @selected(request('priority') === 'low')>Low</option>
                            <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                            <option value="high" @selected(request('priority') === 'high')>High</option>
                        </select>
                    </div>
                    <div>
                        <select name="status" class="input text-sm">
                            <option value="">All</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                    <a href="{{ route('todos.index') }}" class="btn-ghost text-sm">Clear</a>
                </form>
            </div>

            <div class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                @forelse ($todos as $todo)
                    <div class="flex items-start gap-3 py-3 group {{ $todo->is_completed ? 'opacity-60' : '' }}">
                        <form method="POST" action="{{ route('todos.toggle', $todo) }}" class="mt-0.5">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-5 h-5 rounded border-2 flex items-center justify-center transition
                                {{ $todo->is_completed
                                    ? 'bg-green-500 border-green-500 dark:bg-green-400 dark:border-green-400'
                                    : 'border-slate-200 dark:border-slate-700 hover:border-cyan-400' }}">
                                @if ($todo->is_completed)
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                        </form>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-medium {{ $todo->is_completed ? 'line-through text-slate-400 dark:text-slate-500' : 'text-slate-900 dark:text-slate-100' }}">
                                    {{ $todo->title }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded font-medium
                                    @if ($todo->priority === 'high') bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300
                                    @elseif ($todo->priority === 'medium') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300
                                    @else bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 @endif">
                                    {{ ucfirst($todo->priority) }}
                                </span>
                                @if ($todo->category)
                                    <span class="text-xs px-2 py-0.5 rounded bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-300">{{ $todo->category }}</span>
                                @endif
                                @if ($todo->due_date)
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $todo->due_date->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition">
                            <button @click="$dispatch('open-modal', 'edit-todo-{{ $todo->id }}')" class="btn-ghost text-xs p-1">Edit</button>
                            <form method="POST" action="{{ route('todos.destroy', $todo) }}" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger text-xs p-1">Delete</button>
                            </form>
                        </div>
                    </div>

                    <x-modal name="edit-todo-{{ $todo->id }}" focusable>
                        <form method="POST" action="{{ route('todos.update', $todo) }}" class="p-6 space-y-4">
                            @csrf @method('PATCH')
                            <h3 class="font-semibold text-lg text-slate-900 dark:text-slate-100">Edit Task</h3>
                            <div>
                                <x-input-label for="title-{{ $todo->id }}">Title</x-input-label>
                                <input type="text" name="title" id="title-{{ $todo->id }}" value="{{ $todo->title }}" required class="input">
                            </div>
                            <div>
                                <x-input-label for="desc-{{ $todo->id }}">Description</x-input-label>
                                <textarea name="description" id="desc-{{ $todo->id }}" rows="2" class="input">{{ $todo->description }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-input-label for="priority-{{ $todo->id }}">Priority</x-input-label>
                                    <select name="priority" id="priority-{{ $todo->id }}" class="input">
                                        <option value="low" @selected($todo->priority === 'low')>Low</option>
                                        <option value="medium" @selected($todo->priority === 'medium')>Medium</option>
                                        <option value="high" @selected($todo->priority === 'high')>High</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="category-{{ $todo->id }}">Category</x-input-label>
                                    <input type="text" name="category" id="category-{{ $todo->id }}" value="{{ $todo->category }}" class="input">
                                </div>
                            </div>
                            <div>
                                <x-input-label for="due_date-{{ $todo->id }}">Due Date</x-input-label>
                                <input type="date" name="due_date" id="due_date-{{ $todo->id }}" value="{{ $todo->due_date?->format('Y-m-d') }}" class="input">
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="$dispatch('close-modal', 'edit-todo-{{ $todo->id }}')" class="btn-secondary text-sm">Cancel</button>
                                <button type="submit" class="btn-primary text-sm">Update</button>
                            </div>
                        </form>
                    </x-modal>
                @empty
                    <div class="empty-state py-12">
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No tasks yet</p>
                        <p class="text-sm text-slate-500 mb-4">Add a task above to get started.</p>
                    </div>
                @endforelse
            </div>

            @if ($todos->hasPages())
                <div class="mt-6">{{ $todos->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
