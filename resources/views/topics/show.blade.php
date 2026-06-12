<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $topic->title }}</h2>
                @if ($topic->description)
                    <p class="text-sm text-gray-500 mt-0.5">{{ $topic->description }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('topics.edit', $topic) }}" class="btn-ghost text-sm">Edit</a>
                <a href="{{ route('topics.index') }}" class="btn-ghost text-sm">All topics</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Sections --}}
            @if ($topic->sections->isNotEmpty())
                <div x-data="{ active: 0 }">
                    <div class="flex gap-1 mb-6 overflow-x-auto pb-1 border-b border-gray-200 dark:border-gray-700/50">
                        @foreach ($topic->sections as $i => $section)
                            <button
                                @click="active = {{ $i }}"
                                :class="active === {{ $i }} ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="shrink-0 px-4 py-2 text-sm font-medium border-b-2 capitalize transition"
                            >
                                {{ $section->type }}
                            </button>
                        @endforeach
                    </div>

                    @foreach ($topic->sections as $i => $section)
                        <div x-show="active === {{ $i }}" class="card p-6 prose-custom">
                            {!! Str::of($section->content)->markdown() !!}
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/50 flex gap-2">
                                <form method="POST" action="{{ route('sections.destroy', [$topic, $section]) }}" onsubmit="return confirm('Delete this section?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1">No content yet</p>
                    <p class="text-sm text-gray-500">Add your first section below.</p>
                </div>
            @endif

            {{-- Add Section Form --}}
            <div class="card p-6">
                <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100 mb-3">Add Section</h3>
                <form method="POST" action="{{ route('sections.store', $topic) }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                            <select name="type" class="input text-xs">
                                <option value="overview">Overview</option>
                                <option value="notes">Notes</option>
                                <option value="examples">Examples</option>
                                <option value="resources">Resources</option>
                                <option value="summary">Summary</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Custom Type Label</label>
                            <input type="text" name="custom_type" placeholder="e.g. Key Terms, Formulas" class="input text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Content (Markdown)</label>
                        <textarea name="content" rows="6" required class="input font-mono text-xs" placeholder="Write your content in Markdown..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary text-sm">Add Section</button>
                </form>
            </div>

            {{-- End session --}}
            <div class="flex justify-end">
                <button onclick="endSession()" class="btn-ghost text-sm border border-gray-200 dark:border-gray-700">
                    Done studying
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        async function endSession() {
            await fetch('{{ route('sessions.end', $topic) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            });
        }

        window.addEventListener('beforeunload', () => {
            navigator.sendBeacon(
                '{{ route('sessions.end', $topic) }}',
                new URLSearchParams({ _token: document.querySelector('meta[name="csrf-token"]').content })
            );
        });
    </script>
    @endpush
</x-app-layout>
