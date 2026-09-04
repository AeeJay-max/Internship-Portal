@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Lecturers</h1>
                            <p class="text-gray-500 text-sm mt-0.5">Manage faculty members</p>
                        </div>
                        <a href="{{ route('admin.portal.lecturers.create') }}"
                           class="bg-blue-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-800 transition">
                            + Add Lecturer
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
                    @endif

                    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Title</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Faculty</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Schedule Slots</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @forelse($lecturers as $lecturer)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                                 style="background: #011C3E;">
                                                {{ strtoupper(substr($lecturer->name, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-gray-800 text-sm">{{ $lecturer->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $lecturer->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $lecturer->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $lecturer->faculty ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $lecturer->schedule_slots_count }}</td>
                                    <td class="px-6 py-4 text-sm text-right flex items-center gap-3 justify-end">
                                        <a href="{{ route('admin.portal.lecturers.edit', $lecturer) }}"
                                           class="text-blue-600 hover:underline font-medium">Edit</a>
                                        <form method="POST" action="{{ route('admin.portal.lecturers.destroy', $lecturer) }}"
                                              onsubmit="return confirm('Delete this lecturer?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No lecturers found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
