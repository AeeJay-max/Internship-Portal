@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Programs</h1>
                            <p class="text-gray-500 text-sm mt-1">
                                @if(Auth::user()->isSuperAdmin())
                                    Full program management — add, edit, delete, manage home slider
                                @else
                                    Manage program images, descriptions and home slider
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
                            @if(Auth::user()->isSuperAdmin())
                                <a href="{{ route('admin.programs.create') }}"
                                   class="px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                                   style="background: #011C3E;">
                                    + New Program
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
                    @endif

                    {{-- HOME SLIDER PREVIEW --}}
                    <div class="bg-white rounded-2xl shadow border border-blue-100 p-6 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Home Page Slider
                                </h2>
                                <p class="text-xs text-gray-400 mt-0.5">Click ★ on any program below to add or remove it from the home page slider. Max 6.</p>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full" style="background: #eff6ff; color: #1d4ed8;">
                {{ $featured->count() }} / 6 shown
            </span>
                        </div>

                        @if($featured->isEmpty())
                            <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-400">
                                No programs in slider yet. Use the ★ button in the table below to add programs.
                            </div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                                @foreach($featured as $f)
                                    <div class="rounded-xl overflow-hidden border border-gray-200">
                                        {{-- Image or gradient --}}
                                        <div class="h-20 relative overflow-hidden">
                                            @if($f->image_path)
                                                <div class="absolute inset-0 bg-cover bg-center"
                                                     style="background-image: url('{{ asset('storage/' . $f->image_path) }}');"></div>
                                                <div class="absolute inset-0" style="background: rgba(2,62,138,0.4);"></div>
                                            @else
                                                <div class="absolute inset-0" style="background: linear-gradient(135deg, #011C3E, #611818);"></div>
                                            @endif
                                            <div class="absolute inset-0 flex items-center justify-center p-2">
                                                <p class="text-white text-xs font-semibold leading-tight text-center">{{ $f->name }}</p>
                                            </div>
                                        </div>
                                        <div class="px-2 py-1.5 flex items-center justify-between bg-gray-50">
                                            @php
                                                $lc = ['bachelor'=>['BSc','#eff6ff','#1d4ed8'],'master'=>['MSc','#f5f3ff','#7c3aed'],'phd'=>['PhD','#fef3c7','#b45309']];
                                                $lb = $lc[$f->degree_level] ?? ['—','#f3f4f6','#6b7280'];
                                            @endphp
                                            <span class="text-xs font-bold px-1.5 py-0.5 rounded-full"
                                                  style="background: {{ $lb[1] }}; color: {{ $lb[2] }}">{{ $lb[0] }}</span>
                                            <form method="POST" action="{{ route('admin.programs.toggleFeatured', $f) }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium transition">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ALL PROGRAMS grouped by degree level --}}
                    @foreach(['bachelor' => "Bachelor's Programs", 'master' => "Master's Programs", 'phd' => 'PhD Programs'] as $level => $label)
                        @php $levelPrograms = $programs->getCollection()->filter(fn($p) => $p->degree_level === $level); @endphp
                        @if($levelPrograms->isNotEmpty())
                            <div class="mb-8">
                                <h2 class="text-sm font-bold tracking-widest uppercase mb-3" style="color: #611818;">{{ $label }}</h2>
                                <div class="bg-white rounded-xl shadow overflow-hidden">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cover</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Program</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Faculty</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slider</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                        @foreach($levelPrograms as $program)
                                            <tr class="hover:bg-gray-50 transition {{ $program->trashed() ? 'opacity-50' : '' }}">

                                                {{-- Cover photo thumbnail --}}
                                                <td class="px-4 py-3">
                                                    <div class="w-16 h-10 rounded-lg overflow-hidden border border-gray-200 shrink-0">
                                                        @if($program->image_path)
                                                            <img src="{{ asset('storage/' . $program->image_path) }}"
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-gray-300"
                                                                 style="background: #f0f4f8;">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-800">{{ $program->name }}</div>
                                                    @if($program->description)
                                                        <div class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($program->description, 60) }}</div>
                                                    @else
                                                        <div class="text-xs text-gray-300 mt-0.5 italic">No description</div>
                                                    @endif
                                                </td>

                                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $program->faculty ?? '—' }}</td>

                                                <td class="px-4 py-3">
                                                    @if($program->trashed())
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Deleted</span>
                                                    @elseif($program->is_active)
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Inactive</span>
                                                    @endif
                                                </td>

                                                {{-- Featured toggle --}}
                                                <td class="px-4 py-3">
                                                    @if(!$program->trashed() && $program->is_active)
                                                        <form method="POST" action="{{ route('admin.programs.toggleFeatured', $program) }}">
                                                            @csrf
                                                            <button type="submit" class="text-xl transition-all"
                                                                    title="{{ $program->is_featured ? 'Remove from slider' : 'Add to slider' }}">
                                                                @if($program->is_featured)
                                                                    <span style="color: #f59e0b;">★</span>
                                                                @else
                                                                    <span class="text-gray-300 hover:text-yellow-400">☆</span>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-200">★</span>
                                                    @endif
                                                </td>

                                                {{-- Actions --}}
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        @if($program->trashed())
                                                            @if(Auth::user()->isSuperAdmin())
                                                                <form method="POST" action="{{ route('admin.programs.restore', $program->id) }}">
                                                                    @csrf
                                                                    <button class="text-xs font-medium text-green-600 hover:underline">Restore</button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            {{-- Edit: both roles but different forms --}}
                                                            <a href="{{ route('admin.programs.edit', $program) }}"
                                                               class="text-xs font-medium text-blue-600 hover:underline">
                                                                {{ Auth::user()->isSuperAdmin() ? 'Edit' : 'Update Content' }}
                                                            </a>
                                                            @if(Auth::user()->isSuperAdmin())
                                                                <form method="POST" action="{{ route('admin.programs.destroy', $program) }}"
                                                                      onsubmit="return confirm('Delete this program?')">
                                                                    @csrf @method('DELETE')
                                                                    <button class="text-xs font-medium text-red-500 hover:underline">Delete</button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="mt-4">{{ $programs->links() }}</div>
                </div>

            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
@endsection
