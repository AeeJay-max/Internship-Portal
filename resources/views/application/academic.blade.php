@extends('application.layout')

@section('step-content')
<form method="POST" action="{{ route('application.academic.store') }}" class="space-y-6">
    @csrf

    <div class="border-b border-slate-200 pb-4 mb-6">
        <h2 class="text-xl font-extrabold text-slate-900">Step 2 — Academic Background</h2>
        <p class="text-xs text-slate-500">Provide information regarding your education and current studies.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="institution_type" class="block text-xs font-bold text-slate-700 uppercase mb-1">Institution Type *</label>
            <select name="institution_type" id="institution_type" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                <option value="">-- Select Institution Type --</option>
                <option value="University" {{ old('institution_type', $academic->institution_type ?? '') == 'University' ? 'selected' : '' }}>University</option>
                <option value="Polytechnic" {{ old('institution_type', $academic->institution_type ?? '') == 'Polytechnic' ? 'selected' : '' }}>Polytechnic</option>
                <option value="College" {{ old('institution_type', $academic->institution_type ?? '') == 'College' ? 'selected' : '' }}>College</option>
                <option value="Vocational Center" {{ old('institution_type', $academic->institution_type ?? '') == 'Vocational Center' ? 'selected' : '' }}>Vocational Center</option>
                <option value="Other" {{ old('institution_type', $academic->institution_type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div>
            <label for="school_name_select" class="block text-xs font-bold text-slate-700 uppercase mb-1">Institution Name *</label>
            
            {{-- Filtered Institution Dropdown --}}
            <select id="school_name_select" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                <option value="">-- Please Select Institution Type First --</option>
            </select>

            {{-- Manual write-in field if institution is not in the list --}}
            <div id="manual_school_wrapper" class="mt-3 hidden">
                <label for="school_name_manual" class="block text-[11px] font-bold text-amber-800 uppercase mb-1">Specify Institution Name *</label>
                <input type="text" 
                       id="school_name_manual" 
                       placeholder="Enter your institution name manually" 
                       class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
            </div>

            <input type="hidden" name="school_name" id="school_name" value="{{ old('school_name', $academic->school_name ?? '') }}" required>
            <p id="school_name_error" class="hidden text-xs text-rose-600 font-semibold mt-1">Please select or type your institution name.</p>
            <p class="text-[11px] text-slate-500 mt-1">Select from formal Zimbabwean institutions or choose "Other / Not Listed" to enter manually.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Programme / Course of Study *</label>
            <input type="text" name="program_of_study" value="{{ old('program_of_study', $academic->program_of_study ?? '') }}" required placeholder="e.g. BSc Computer Science, Diploma in Sports Management" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Faculty *</label>
            <input type="text" name="field_of_study" value="{{ old('field_of_study', $academic->field_of_study ?? '') }}" required placeholder="e.g. Science & Technology, Commerce, Humanities, Arts & Education" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Level / Year *</label>
            <select name="current_year_level" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                <option value="">Select Level</option>
                <option value="Year 1" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 1' ? 'selected' : '' }}>Year 1</option>
                <option value="Year 2" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 2' ? 'selected' : '' }}>Year 2</option>
                <option value="Year 3" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 3' ? 'selected' : '' }}>Year 3</option>
                <option value="Year 4" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 4' ? 'selected' : '' }}>Year 4</option>
                <option value="Graduated" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Graduated' ? 'selected' : '' }}>Graduated / Postgraduate</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expected Graduation Date</label>
            <input type="date" name="expected_graduation_date" value="{{ old('expected_graduation_date', optional($academic->expected_graduation_date ?? null)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Academic Performance / GPA (Optional)</label>
        <input type="text" name="gpa" value="{{ old('gpa', $academic->gpa ?? '') }}" placeholder="e.g. 3.5 GPA or 2.1 Pass Class" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.personal') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            &larr; Back to Personal
        </a>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-[#005A2B] hover:bg-[#00421F] text-white font-bold text-sm uppercase tracking-wider transition shadow">
            Save & Continue to Preferences &rarr;
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect   = document.getElementById('institution_type');
        const schoolSelect = document.getElementById('school_name_select');
        const manualWrap   = document.getElementById('manual_school_wrapper');
        const manualInput  = document.getElementById('school_name_manual');
        const hiddenInput  = document.getElementById('school_name');
        const errorText    = document.getElementById('school_name_error');
        const form         = typeSelect ? typeSelect.closest('form') : null;

        const institutions = {
            'University': [
                'Africa University (AU)',
                'Arrupe Jesuit University (AJU)',
                'Bindura University of Science Education (BUSE)',
                'Catholic University of Zimbabwe (CUZ)',
                'Chinhoyi University of Technology (CUT)',
                'Great Zimbabwe University (GZU)',
                'Gwanda State University (GSU)',
                'Harare Institute of Technology (HIT)',
                'Lupane State University (LSU)',
                'Manicaland State University of Applied Sciences (MSUAS)',
                'Marondera University of Agricultural Sciences and Technology (MUAST)',
                'Midlands State University (MSU)',
                'National University of Science and Technology (NUST)',
                'Reformed Church University (RCU)',
                'Solusi University',
                'University of Zimbabwe (UZ)',
                'Women\'s University in Africa (WUA)',
                'Zimbabwe Ezekiel Guti University (ZEGU)',
                'Zimbabwe Open University (ZOU)'
            ],
            'Polytechnic': [
                'Bulawayo Polytechnic',
                'Gweru Polytechnic',
                'Harare Polytechnic',
                'Joshua Mqabuko Nkomo Polytechnic',
                'Kushinga Phikelela Polytechnic',
                'Kwekwe Polytechnic',
                'Masvingo Polytechnic',
                'Mutare Polytechnic'
            ],
            'College': [
                'Belvedere Technical Teachers College',
                'Bondolfi Teachers College',
                'Chibero Agricultural College',
                'Chitungwiza Central Hospital School of Nursing',
                'Esigodini Agricultural College',
                'Gwebi Agricultural College',
                'Harare Central Hospital School of Nursing (Sally Mugabe)',
                'Hillside Teachers College',
                'Madziwa Teachers College',
                'Marymount Teachers College',
                'Masvingo Teachers College',
                'Mkoba Teachers College',
                'Mlezu Agricultural College',
                'Morgan ZINTEC College',
                'Morgenster Teachers College',
                'Mpilo Central Hospital School of Nursing',
                'Mutare Teachers College',
                'Nyadire Teachers College',
                'Parirenyatwa School of Nursing',
                'Rio Tinto Agricultural College',
                'Seke Teachers College',
                'United Bulawayo Hospitals (UBH) School of Nursing',
                'United College of Education (UCE)',
                'Zimbabwe College of Forestry'
            ],
            'Vocational Center': [
                'Dadaya Vocational Training Centre',
                'Harare Vocational Training Centre',
                'Magamba Vocational Training Centre',
                'Msasa Vocational Training Centre',
                'Mupfure Self-Help College / Vocational Training Centre',
                'Mushagashe Vocational Training Centre',
                'Ruwa Vocational Training Centre',
                'St. Peters Kubatana Vocational Centre',
                'Tabudirira Vocational Training Centre',
                'Westgate Vocational Training Centre'
            ],
            'Other': []
        };

        function setHiddenValue(val) {
            hiddenInput.value = val;
            if (val.trim()) {
                if (errorText) errorText.classList.add('hidden');
                hiddenInput.setCustomValidity('');
            }
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function populateInstitutions(selectedType, preserveValue) {
            const list = institutions[selectedType] || [];
            schoolSelect.innerHTML = '';

            if (!selectedType) {
                schoolSelect.disabled = true;
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = '-- Please Select Institution Type First --';
                schoolSelect.appendChild(opt);
                manualWrap.classList.add('hidden');
                setHiddenValue('');
                return;
            }

            schoolSelect.disabled = false;

            if (selectedType === 'Other') {
                schoolSelect.classList.add('hidden');
                manualWrap.classList.remove('hidden');
                if (preserveValue) {
                    manualInput.value = preserveValue;
                    setHiddenValue(preserveValue);
                } else {
                    setHiddenValue(manualInput.value.trim());
                }
                return;
            }

            schoolSelect.classList.remove('hidden');

            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = `-- Select ${selectedType} --`;
            schoolSelect.appendChild(defaultOpt);

            let matched = false;
            list.forEach(inst => {
                const opt = document.createElement('option');
                opt.value = inst;
                opt.textContent = inst;
                if (preserveValue && preserveValue.toLowerCase() === inst.toLowerCase()) {
                    opt.selected = true;
                    matched = true;
                }
                schoolSelect.appendChild(opt);
            });

            // "Other / Not Listed" option
            const otherOpt = document.createElement('option');
            otherOpt.value = '__other__';
            otherOpt.textContent = '-- Other / Not Listed (Type Manually) --';
            schoolSelect.appendChild(otherOpt);

            if (preserveValue && !matched) {
                otherOpt.selected = true;
                manualWrap.classList.remove('hidden');
                manualInput.value = preserveValue;
                setHiddenValue(preserveValue);
            } else if (matched) {
                manualWrap.classList.add('hidden');
                manualInput.value = '';
                setHiddenValue(preserveValue);
            } else {
                manualWrap.classList.add('hidden');
                manualInput.value = '';
                setHiddenValue('');
            }
        }

        typeSelect.addEventListener('change', function () {
            populateInstitutions(this.value, null);
        });

        schoolSelect.addEventListener('change', function () {
            if (this.value === '__other__') {
                manualWrap.classList.remove('hidden');
                manualInput.focus();
                setHiddenValue(manualInput.value.trim());
            } else if (this.value) {
                manualWrap.classList.add('hidden');
                manualInput.value = '';
                setHiddenValue(this.value);
            } else {
                manualWrap.classList.add('hidden');
                manualInput.value = '';
                setHiddenValue('');
            }
        });

        manualInput.addEventListener('input', function () {
            setHiddenValue(this.value.trim());
        });

        if (form) {
            form.addEventListener('submit', function (e) {
                const currentVal = hiddenInput.value.trim();
                if (!currentVal) {
                    e.preventDefault();
                    if (errorText) errorText.classList.remove('hidden');
                    if (!manualWrap.classList.contains('hidden')) {
                        manualInput.focus();
                    } else {
                        schoolSelect.focus();
                    }
                }
            });
        }

        // Initialize on load
        const initialType = typeSelect.value;
        const initialSchool = hiddenInput.value;
        if (initialType) {
            populateInstitutions(initialType, initialSchool);
        } else {
            populateInstitutions('', null);
        }
    });
</script>
@endpush

