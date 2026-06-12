<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Achievement</h2>
            <a href="{{ route('achievements.index') }}" class="btn-ghost text-sm">Back to achievements</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card p-6">
                <form method="POST" action="{{ route('achievements.update', $achievement) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <x-input-label for="title">Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title', $achievement->title) }}" required class="input" placeholder="e.g. AWS Certified Solutions Architect">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="description">Description (optional)</x-input-label>
                        <textarea name="description" id="description" rows="3" class="input" placeholder="What did you accomplish?">{{ old('description', $achievement->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="date">Date</x-input-label>
                            <input type="date" name="date" id="date" value="{{ old('date', $achievement->date->format('Y-m-d')) }}" required class="input">
                            @error('date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="type">Type</x-input-label>
                            <select name="type" id="type" class="input">
                                <option value="certificate" @selected(old('type', $achievement->type) === 'certificate')>Certificate</option>
                                <option value="award" @selected(old('type', $achievement->type) === 'award')>Award</option>
                                <option value="publication" @selected(old('type', $achievement->type) === 'publication')>Publication</option>
                                <option value="skill" @selected(old('type', $achievement->type) === 'skill')>Skill</option>
                                <option value="leadership" @selected(old('type', $achievement->type) === 'leadership')>Leadership</option>
                                <option value="other" @selected(old('type', $achievement->type) === 'other')>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="issuer">Issuer (optional)</x-input-label>
                            <input type="text" name="issuer" id="issuer" value="{{ old('issuer', $achievement->issuer) }}" class="input" placeholder="e.g. Amazon Web Services">
                        </div>
                        <div>
                            <x-input-label for="url">URL (optional)</x-input-label>
                            <input type="url" name="url" id="url" value="{{ old('url', $achievement->url) }}" class="input" placeholder="https://">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <form method="POST" action="{{ route('achievements.destroy', $achievement) }}" onsubmit="return confirm('Delete this achievement?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger text-sm">Delete Achievement</button>
                        </form>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('achievements.index') }}" class="btn-secondary text-sm">Cancel</a>
                            <button type="submit" class="btn-primary text-sm">Update Achievement</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
