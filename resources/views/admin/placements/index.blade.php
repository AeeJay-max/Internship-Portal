@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Internship Placement Management</h1>
                <p class="text-slate-500 text-xs mt-0.5">Assign approved applicants to departments, division units, supervisors, and reporting instructions.</p>
            </div>
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
                                <th class="py-3 px-4">Reference</th>
                                <th class="py-3 px-4">Applicant</th>
                                <th class="py-3 px-4">Preferred Dept</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Assigned Placement</th>
                                <th class="py-3 px-4 text-right">Placement Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($applications as $app)
                                @php $badge = $app->statusBadge(); @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $app->reference_number }}</td>
                                    <td class="py-3.5 px-4 font-semibold">{{ $app->user->name ?? 'Applicant' }}</td>
                                    <td class="py-3.5 px-4">{{ $app->preference->preferredDepartment->name ?? 'General' }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($app->placement)
                                            <span class="font-bold text-emerald-800">{{ $app->placement->department->name ?? 'Placed' }}</span>
                                            <p class="text-[10px] text-slate-400">Supervisor: {{ $app->placement->supervisor_name }}</p>
                                            @if($app->placement->isExpiringSoon())
                                                <span class="inline-block px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[10px] mt-1 shadow-sm">
                                                    ⏳ {{ $app->placement->days_remaining }} Days Remaining (Final Date: {{ $app->placement->end_date ? $app->placement->end_date->format('d M Y') : '' }})
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-amber-800 font-semibold italic">Pending Assignment</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button onclick="openPlacementModal({{ $app->id }}, '{{ $app->reference_number }}', '{{ $app->user->name ?? 'Applicant' }}', '{{ $app->preference->preferred_department_id ?? '' }}')" class="px-4 py-2 rounded-xl bg-blue-900 text-white font-bold text-xs hover:bg-blue-800 transition">
                                            {{ $app->placement ? 'Edit Placement' : 'Assign Placement' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PLACEMENT ASSIGNMENT MODAL --}}
<div id="placementModal" class="fixed inset-0 bg-slate-900/60 flex items-center justify-center hidden z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full space-y-6 my-8">
        <div class="flex justify-between items-center border-b border-slate-200 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Assign Internship Placement</h3>
                <p id="modal-app-info" class="text-xs text-slate-500"></p>
            </div>
            <button onclick="closePlacementModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
        </div>

        <form id="placement-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Assigned Department *</label>
                    <select id="modal_dept_id" name="department_id" required class="w-full rounded-xl border-slate-300 text-xs">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Division / Unit</label>
                    <input type="text" name="division_unit" placeholder="e.g. Systems & Infrastructure Unit" class="w-full rounded-xl border-slate-300 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Supervisor Name *</label>
                    <input type="text" name="supervisor_name" required placeholder="e.g. Eng. T. Moyo" class="w-full rounded-xl border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Supervisor Email</label>
                    <input type="email" name="supervisor_email" placeholder="supervisor@mosrac.gov.zw" class="w-full rounded-xl border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Supervisor Phone</label>
                    <input type="text" name="supervisor_phone" placeholder="+263 77 000 0000" class="w-full rounded-xl border-slate-300 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Placement Location *</label>
                    <input type="text" name="placement_location" required value="Harare HQ" class="w-full rounded-xl border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Start Date *</label>
                    <input type="date" name="start_date" required class="w-full rounded-xl border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">End Date *</label>
                    <input type="date" name="end_date" required class="w-full rounded-xl border-slate-300 text-xs">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">First Day Reporting Instructions *</label>
                <textarea name="reporting_instructions" rows="3" required placeholder="Report to Room 302, 3rd Floor, Mukwati Building at 08:30 AM with National ID and introduction letter..." class="w-full rounded-xl border-slate-300 text-xs"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closePlacementModal()" class="px-5 py-2.5 rounded-xl border border-slate-300 font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 text-white font-bold uppercase hover:bg-emerald-800 transition">
                    Save Placement Details
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPlacementModal(appId, refNo, applicantName, prefDeptId) {
        document.getElementById('modal-app-info').textContent = 'Applicant: ' + applicantName + ' (' + refNo + ')';
        document.getElementById('placement-form').action = '{{ url("/admin/placements") }}/' + appId;
        if (prefDeptId) {
            document.getElementById('modal_dept_id').value = prefDeptId;
        }
        document.getElementById('placementModal').classList.remove('hidden');
    }

    function closePlacementModal() {
        document.getElementById('placementModal').classList.add('hidden');
    }
</script>
@endsection
