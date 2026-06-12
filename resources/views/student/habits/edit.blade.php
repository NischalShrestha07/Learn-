<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Habit</h2>
            <a href="{{ route('habits.index') }}" class="btn-ghost text-sm">Back to habits</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card p-6">
                <form method="POST" action="{{ route('habits.update', $habit) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <x-input-label for="name">Name</x-input-label>
                        <input type="text" name="name" id="name" value="{{ old('name', $habit->name) }}" required class="input" placeholder="e.g. Morning run">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="description">Description (optional)</x-input-label>
                        <textarea name="description" id="description" rows="2" class="input" placeholder="What's this habit about?">{{ old('description', $habit->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="icon">Icon (emoji)</x-input-label>
                            <input type="text" name="icon" id="icon" value="{{ old('icon', $habit->icon ?? '✅') }}" class="input" placeholder="✅">
                            @error('icon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="color">Color (hex)</x-input-label>
                            <input type="text" name="color" id="color" value="{{ old('color', $habit->color ?? '#0891b2') }}" class="input" placeholder="#0891b2">
                            @error('color') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="frequency">Frequency</x-input-label>
                            <select name="frequency" id="frequency" class="input">
                                <option value="daily" @selected(old('frequency', $habit->frequency) === 'daily')>Daily</option>
                                <option value="weekly" @selected(old('frequency', $habit->frequency) === 'weekly')>Weekly</option>
                                <option value="weekdays" @selected(old('frequency', $habit->frequency) === 'weekdays')>Weekdays</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="target_value">Target value</x-input-label>
                            <input type="number" name="target_value" id="target_value" value="{{ old('target_value', $habit->target_value) }}" min="1" class="input">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="target_unit">Target unit</x-input-label>
                        <input type="text" name="target_unit" id="target_unit" value="{{ old('target_unit', $habit->target_unit) }}" class="input" placeholder="e.g. minutes, pages, times">
                        @error('target_unit') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $habit->is_active)) class="rounded border-slate-300 dark:border-slate-600 text-cyan-600 focus:ring-cyan-500">
                        <label for="is_active" class="text-sm text-slate-600 dark:text-slate-400">Active</label>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <form method="POST" action="{{ route('habits.destroy', $habit) }}" onsubmit="return confirm('Delete this habit and all its logs?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger text-sm">Delete Habit</button>
                        </form>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('habits.index') }}" class="btn-secondary text-sm">Cancel</a>
                            <button type="submit" class="btn-primary text-sm">Update Habit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
