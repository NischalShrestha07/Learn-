<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Achievements</h2>
            <a href="{{ route('achievements.create') }}" class="btn-primary text-sm">+ New Achievement</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Total</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['certificate'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Certificates</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['award'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Awards</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['skill'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Skills</p>
                </div>
            </div>

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <select name="type" class="input text-sm">
                            <option value="">All types</option>
                            <option value="certificate" @selected(request('type') === 'certificate')>Certificate</option>
                            <option value="award" @selected(request('type') === 'award')>Award</option>
                            <option value="publication" @selected(request('type') === 'publication')>Publication</option>
                            <option value="skill" @selected(request('type') === 'skill')>Skill</option>
                            <option value="leadership" @selected(request('type') === 'leadership')>Leadership</option>
                            <option value="other" @selected(request('type') === 'other')>Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                    @if (request('type'))
                        <a href="{{ route('achievements.index') }}" class="btn-ghost text-sm">Clear</a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($achievements as $achievement)
                    <div class="card hover:shadow-md transition group">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    @if($achievement->type === 'certificate') bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300
                                    @elseif($achievement->type === 'award') bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300
                                    @elseif($achievement->type === 'publication') bg-cyan-100 dark:bg-cyan-900/40 text-cyan-800 dark:text-cyan-300
                                    @elseif($achievement->type === 'skill') bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300
                                    @elseif($achievement->type === 'leadership') bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300
                                    @else bg-slate-100 dark:bg-slate-700 text-slate-500 @endif
                                ">{{ ucfirst($achievement->type) }}</span>
                                <a href="{{ route('achievements.edit', $achievement) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">{{ $achievement->title }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                {{ $achievement->date->format('M Y') }}
                                @if ($achievement->issuer)
                                    · {{ $achievement->issuer }}
                                @endif
                            </p>
                            @if ($achievement->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-2">{{ $achievement->description }}</p>
                            @endif
                            @if ($achievement->url)
                                <div class="mt-3 pt-3 border-t border-slate-200/70 dark:border-slate-700/60">
                                    <a href="{{ $achievement->url }}" target="_blank" class="link text-xs">View credential →</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No achievements yet</p>
                        <p class="text-sm text-slate-500 mb-4">Track your certificates, awards, and milestones.</p>
                        <a href="{{ route('achievements.create') }}" class="btn-primary text-sm">Add your first achievement</a>
                    </div>
                @endforelse
            </div>

            @if ($achievements->hasPages())
                <div class="mt-6">{{ $achievements->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
