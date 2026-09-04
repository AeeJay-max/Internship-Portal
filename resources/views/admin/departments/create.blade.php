@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Create Ministry Department</h1>
                <p class="text-slate-500 text-xs mt-0.5">Add a new operational department to the Ministry portal.</p>
            </div>
            <a href="{{ route('admin.departments.index') }}" class="text-xs font-bold text-blue-900 hover:underline">&larr; Back to Departments</a>
        </div>

        <form method="POST" action="{{ route('admin.departments.store') }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Department Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Department Code (Short Abbreviation) *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. ICT, HR, SPORT" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Internship Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 10) }}" min="0" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Active Status</label>
                <select name="is_active" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('admin.departments.index') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-xs font-bold">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800">
                    Save Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
