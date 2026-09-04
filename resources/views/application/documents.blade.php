@extends('application.layout')

@section('step-content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h2 class="text-xl font-extrabold text-slate-900">Step 5 — Upload Supporting Documents</h2>
        <p class="text-xs text-slate-500">Upload individual documents. Selected files auto-upload automatically upon selection.</p>
    </div>

    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 font-medium">
            ⚠️ {{ session('warning') }}
        </div>
    @endif

    <div class="space-y-4">
        @foreach($documentTypes as $docType)
            @php
                $uploaded    = $application->documents->firstWhere('document_type_id', $docType->id);
                $allowedLabel = collect(explode(',', $docType->allowed_mimes))->map(fn($m) => strtoupper(trim($m)))->implode(', ');
                $maxMb       = $docType->max_size / 1024;
                $fieldKey    = $docType->code;
                $hasError    = $errors->has("documents.{$fieldKey}");
            @endphp

            <div class="bg-white border rounded-xl p-5 transition-all @if($hasError) border-red-300 bg-red-50 @elseif($uploaded) border-emerald-300 bg-emerald-50/20 @else border-slate-200 @endif">
                <form method="POST" action="{{ route('application.documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="font-bold text-slate-900 text-sm">
                                {{ $docType->name }}
                                @if($docType->is_required)
                                    <span class="text-red-500">*</span>
                                @else
                                    <span class="text-slate-400 text-xs font-normal">(Optional)</span>
                                @endif
                            </span>
                            <p class="text-xs text-slate-400 mt-0.5">Format: {{ $allowedLabel }} | Max {{ $maxMb }}MB</p>
                        </div>
                        @if($uploaded)
                            <span class="text-xs bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full font-bold">✓ Uploaded</span>
                        @endif
                    </div>

                    @if($hasError)
                        <p class="text-red-600 text-xs mb-3">{{ $errors->first("documents.{$fieldKey}") }}</p>
                    @endif

                    <div class="flex items-center gap-4 flex-wrap mt-3">
                        <label class="cursor-pointer px-4 py-2 rounded-lg text-xs font-bold text-white bg-blue-900 hover:bg-blue-800 transition">
                            {{ $uploaded ? 'Replace Document' : 'Choose File' }}
                            <input type="file" name="documents[{{ $fieldKey }}]" class="hidden" onchange="previewAndSubmit(this, '{{ $fieldKey }}')">
                        </label>

                        <span id="preview-{{ $fieldKey }}" class="text-xs text-blue-700 hidden items-center gap-1 font-semibold">
                            <span id="preview-name-{{ $fieldKey }}"></span>
                            <span class="text-slate-400 text-xs">(uploading...)</span>
                        </span>

                        @if($uploaded)
                            <a href="{{ asset('storage/' . $uploaded->file_path) }}" target="_blank" class="text-xs font-bold text-blue-700 underline">
                                View Current Document &rarr;
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.motivation') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            &larr; Back to Motivation
        </a>
        <a href="{{ route('application.review') }}" class="px-8 py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-blue-800 transition shadow">
            Continue to Review & Submit &rarr;
        </a>
    </div>
</div>

@push('scripts')
<script>
    function previewAndSubmit(input, key) {
        const file = input.files[0];
        if (!file) return;

        const preview = document.getElementById('preview-' + key);
        const nameEl  = document.getElementById('preview-name-' + key);
        if (preview && nameEl) {
            nameEl.textContent = file.name;
            preview.classList.remove('hidden');
            preview.classList.add('flex');
        }

        const form = input.closest('form');
        if (form) {
            setTimeout(() => form.submit(), 300);
        }
    }
</script>
@endpush
@endsection
