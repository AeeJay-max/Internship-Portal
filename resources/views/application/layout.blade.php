@extends('layouts.app')

@section('content')

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-slate-900">
                    Ministry Internship Application Wizard
                </h1>
                <p class="text-slate-600 text-sm mt-1">
                    Ministry of Sport, Recreation, Arts & Culture — Reference: <strong class="text-blue-900">{{ $application->reference_number ?? 'Pending' }}</strong>
                </p>
            </div>

            @include('application.partials.steps', ['currentStep' => $currentStep ?? 1])

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3 text-sm font-medium">
                    <span class="text-emerald-600 text-lg">✓</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl flex items-center gap-3 text-sm font-medium">
                    <span class="text-lg">✗</span>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl">
                    <p class="font-bold mb-2 text-sm">Please review the form errors:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl p-8 border border-slate-200/80">
                @yield('step-content')
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            const autosaveUrl = '{{ route('application.autosave') }}';
            const csrfToken   = '{{ csrf_token() }}';

            const path = window.location.pathname;
            let currentStep = null;
            if      (path.includes('/personal'))     currentStep = 'personal';
            else if (path.includes('/academic'))     currentStep = 'academic';
            else if (path.includes('/preferences'))  currentStep = 'preferences';
            else if (path.includes('/motivation'))   currentStep = 'motivation';

            if (!currentStep) return;

            const progressBar  = document.getElementById('wizard-progress-bar');
            const progressText = document.getElementById('wizard-progress-text');
            let debounceTimer  = null;
            let saveIndicator  = null;

            function createSaveIndicator() {
                const el = document.createElement('span');
                el.id = 'autosave-indicator';
                el.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#011C3E;color:white;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;opacity:0;transition:opacity 0.3s;z-index:9999;';
                el.textContent = '✓ Saved';
                document.body.appendChild(el);
                return el;
            }

            function showSaved() {
                if (!saveIndicator) saveIndicator = createSaveIndicator();
                saveIndicator.textContent = '✓ Autosaved';
                saveIndicator.style.background = '#059669';
                saveIndicator.style.opacity = '1';
                setTimeout(() => { saveIndicator.style.opacity = '0'; }, 2000);
            }

            function showSaving() {
                if (!saveIndicator) saveIndicator = createSaveIndicator();
                saveIndicator.textContent = '⟳ Saving draft...';
                saveIndicator.style.background = '#011C3E';
                saveIndicator.style.opacity = '1';
            }

            function updateProgressBar(percentage) {
                if (progressBar)  progressBar.style.width = percentage + '%';
                if (progressText) progressText.textContent = percentage + '% complete';
            }

            function getFormData() {
                const form = document.querySelector('form');
                if (!form) return {};
                const fd   = new FormData(form);
                const data = {};
                for (const [key, val] of fd.entries()) {
                    if (key === '_token' || key === '_method') continue;
                    data[key] = val;
                }
                return data;
            }

            function doAutosave() {
                const data = getFormData();
                if (Object.keys(data).length === 0) return;
                showSaving();

                fetch(autosaveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ step: currentStep, data: data })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.ok) {
                        showSaved();
                        if (res.percentage !== undefined) updateProgressBar(res.percentage);
                    } else {
                        if (saveIndicator) saveIndicator.style.opacity = '0';
                    }
                })
                .catch(() => { if (saveIndicator) saveIndicator.style.opacity = '0'; });
            }

            document.addEventListener('input', function(e) {
                if (!e.target.closest('form')) return;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(doAutosave, 1000);
            });

            document.addEventListener('change', function(e) {
                if (!e.target.closest('form')) return;
                if (['SELECT', 'INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(doAutosave, 500);
                }
            });
        })();
    </script>
@endpush
