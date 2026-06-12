<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Resource Library</h2>
            <button @click="$dispatch('open-modal', 'resource-modal')" class="btn-primary text-sm">+ Add Resource</button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search resources..." class="input text-sm">
                    </div>
                    <div>
                        <select name="type" class="input text-sm">
                            <option value="">All types</option>
                            <option value="link" @selected(request('type') === 'link')>Link</option>
                            <option value="pdf" @selected(request('type') === 'pdf')>PDF</option>
                            <option value="video" @selected(request('type') === 'video')>Video</option>
                            <option value="book" @selected(request('type') === 'book')>Book</option>
                            <option value="article" @selected(request('type') === 'article')>Article</option>
                            <option value="other" @selected(request('type') === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <select name="topic_id" class="input text-sm">
                            <option value="">All topics</option>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}" @selected(request('topic_id') == $topic->id)>{{ $topic->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="favourites" id="fav" value="1" @checked(request()->boolean('favourites')) class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        <label for="fav" class="text-sm text-gray-600 dark:text-gray-400">Favourites</label>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($resources as $resource)
                    <div class="card hover:shadow-md transition group">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-2">
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    @if($resource->type === 'link') bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300
                                    @elseif($resource->type === 'pdf') bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300
                                    @elseif($resource->type === 'video') bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300
                                    @elseif($resource->type === 'book') bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300
                                    @elseif($resource->type === 'article') bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-500 @endif
                                ">{{ ucfirst($resource->type) }}</span>
                                <form method="POST" action="{{ route('resources.favourite', $resource) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-lg {{ $resource->is_favourite ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600 hover:text-yellow-400' }} transition">★</button>
                                </form>
                            </div>
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100 line-clamp-1">{{ $resource->title }}</h3>
                            @if ($resource->description)
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-1">{{ $resource->description }}</p>
                            @endif
                            @if ($resource->topic)
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2">{{ $resource->topic->title }}</p>
                            @endif
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/50">
                                @if ($resource->url)
                                    <a href="{{ $resource->url }}" target="_blank" class="link text-xs">Open link →</a>
                                @else
                                    <span></span>
                                @endif
                                <form method="POST" action="{{ route('resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1">No resources saved</p>
                        <p class="text-sm text-gray-500 mb-4">Save links, articles, and study materials.</p>
                    </div>
                @endforelse
            </div>

            @if ($resources->hasPages())
                <div class="mt-6">{{ $resources->links() }}</div>
            @endif
        </div>
    </div>

    <x-modal name="resource-modal" focusable>
        <form method="POST" action="{{ route('resources.store') }}" class="p-6 space-y-4">
            @csrf
            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">Add Resource</h3>
            <div>
                <x-input-label for="rtitle">Title</x-input-label>
                <input type="text" name="title" id="rtitle" required class="input">
            </div>
            <div>
                <x-input-label for="rurl">URL</x-input-label>
                <input type="url" name="url" id="rurl" class="input" placeholder="https://...">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="rtype">Type</x-input-label>
                    <select name="type" id="rtype" class="input">
                        <option value="link">Link</option>
                        <option value="pdf">PDF</option>
                        <option value="video">Video</option>
                        <option value="book">Book</option>
                        <option value="article">Article</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="rtopic">Topic</x-input-label>
                    <select name="topic_id" id="rtopic" class="input">
                        <option value="">None</option>
                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <x-input-label for="rdesc">Description</x-input-label>
                <textarea name="description" id="rdesc" rows="2" class="input"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$dispatch('close-modal', 'resource-modal')" class="btn-secondary text-sm">Cancel</button>
                <button type="submit" class="btn-primary text-sm">Add Resource</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
