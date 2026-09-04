@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Ministry Departments</h1>
                <p class="text-slate-500 text-xs mt-0.5">Manage Ministry departments and internship capacity.</p>
            </div>
            <a href="{{ route('admin.departments.create') }}" class="px-5 py-2.5 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800 transition">
                + Create Department
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            @include('admin.partials.sidebar')

            <div class="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase bg-slate-50">
                                <th class="py-3 px-4">Code</th>
                                <th class="py-3 px-4">Department Name</th>
                                <th class="py-3 px-4">Contact Email</th>
                                <th class="py-3 px-4">Capacity</th>
                                <th class="py-3 px-4">Active Vacancies</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($departments as $dept)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $dept->code }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $dept->name }}</td>
                                    <td class="py-3.5 px-4">{{ $dept->contact_email ?? '—' }}</td>
                                    <td class="py-3.5 px-4">{{ $dept->capacity ?? 'Open' }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-blue-900">{{ $dept->opportunities_count }}</td>
                                    <td class="py-3.5 px-4 text-right space-x-2">
                                        <a href="{{ route('admin.departments.edit', $dept->id) }}" class="font-bold text-blue-900 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
