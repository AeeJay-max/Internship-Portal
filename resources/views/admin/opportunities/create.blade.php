@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Create Internship Opportunity</h1>
                <p class="text-slate-500 text-xs mt-0.5">Advertise a specific internship opportunity.</p>
            </div>
            <a href="{{ route('admin.opportunities.index') }}" class="text-xs font-bold text-blue-900 hover:underline">&larr; Back to Opportunities</a>
        </div>

        <form method="POST" action="{{ route('admin.opportunities.store') }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Opportunity Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. ICT Software Support Internship" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Department *</label>
                    <select name="department_id" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description *</label>
                <textarea name="description" rows="4" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Requirements</label>
                <textarea name="requirements" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('requirements') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Positions Count *</label>
                    <input type="number" name="positions_count" value="{{ old('positions_count', 5) }}" min="1" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Duration (Months) *</label>
                    <input type="number" name="duration_months" value="{{ old('duration_months', 6) }}" min="1" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="filled" {{ old('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Opening Date</label>
                    <input type="date" name="opening_date" value="{{ old('opening_date', date('Y-m-d')) }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Closing Date</label>
                    <input type="date" name="closing_date" value="{{ old('closing_date') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('admin.opportunities.index') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-xs font-bold">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800">
                    Publish Opportunity
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
