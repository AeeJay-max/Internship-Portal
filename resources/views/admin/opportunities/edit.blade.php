@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Edit Opportunity: {{ $opportunity->title }}</h1>
                <p class="text-slate-500 text-xs mt-0.5">Update opportunity posting details.</p>
            </div>
            <a href="{{ route('admin.opportunities.index') }}" class="text-xs font-bold text-blue-900 hover:underline">&larr; Back to Opportunities</a>
        </div>

        <form method="POST" action="{{ route('admin.opportunities.update', $opportunity->id) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Opportunity Title *</label>
                    <input type="text" name="title" value="{{ old('title', $opportunity->title) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Department *</label>
                    <select name="department_id" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $opportunity->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description *</label>
                <textarea name="description" rows="4" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $opportunity->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Requirements</label>
                <textarea name="requirements" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('requirements', $opportunity->requirements) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Positions Count *</label>
                    <input type="number" name="positions_count" value="{{ old('positions_count', $opportunity->positions_count) }}" min="1" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Duration (Months) *</label>
                    <input type="number" name="duration_months" value="{{ old('duration_months', $opportunity->duration_months) }}" min="1" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="open" {{ old('status', $opportunity->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="draft" {{ old('status', $opportunity->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="closed" {{ old('status', $opportunity->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="filled" {{ old('status', $opportunity->status) == 'filled' ? 'selected' : '' }}>Filled</option>
                        <option value="archived" {{ old('status', $opportunity->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                <button type="button" onclick="if(confirm('Archive this opportunity?')) document.getElementById('delete-opp-form').submit();" class="px-5 py-2.5 rounded-xl border border-rose-300 text-rose-600 text-xs font-bold hover:bg-rose-50">
                    Archive Opportunity
                </button>
                <div class="flex gap-3">
                    <a href="{{ route('admin.opportunities.index') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-xs font-bold">Cancel</a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800">
                        Update Opportunity
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-opp-form" method="POST" action="{{ route('admin.opportunities.destroy', $opportunity->id) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
