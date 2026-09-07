@extends('application.layout')

@section('step-content')
<div class="space-y-6">
    <div class="border-b border-emerald-100 pb-4">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-700 text-white font-bold text-sm">5</span>
            <div>
                <h2 class="text-xl font-black text-slate-900">Step 5 — Upload Supporting Documents</h2>
                <p class="text-xs text-slate-600">Combine all required documents into a single PDF file (max 10MB) before uploading.</p>
            </div>
        </div>
    </div>

    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Single PDF Requirement Banner -->
    <div class="bg-amber-500/10 border-l-4 border-amber-500 p-4 rounded-r-xl">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-amber-900 space-y-1">
                <p class="font-bold text-sm">Mandatory Document Packaging Notice</p>
                <p>The Ministry requires all 5 supporting documents to be merged into <strong>ONE single PDF document</strong> before uploading. Applications submitted with missing documents or in image formats (JPG/PNG) will be rejected.</p>
            </div>
        </div>
    </div>

    <!-- Required Items Checklist Card -->
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-3">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Mandatory 5-in-1 Document Checklist
        </h3>
        <p class="text-xs text-slate-600">Ensure your single PDF contains the following items in order:</p>

        <div class="grid md:grid-cols-2 gap-2 text-xs pt-1">
            <div class="flex items-center gap-2 p-2.5 bg-white rounded-lg border border-slate-200 text-slate-700 font-medium">
                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">1</span>
                National Identity Document / Passport
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-white rounded-lg border border-slate-200 text-slate-700 font-medium">
                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">2</span>
                Academic Results / Certificates
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-white rounded-lg border border-slate-200 text-slate-700 font-medium">
                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">3</span>
                Current Semester / Term Results Statement
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-white rounded-lg border border-slate-200 text-slate-700 font-medium">
                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">4</span>
                Comprehensive Curriculum Vitae (CV)
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-white rounded-lg border border-slate-200 text-slate-700 font-medium md:col-span-2">
                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">5</span>
                Official University / College Attachment Clearance Letter
            </div>
        </div>
    </div>

    <!-- Upload Card Form -->
    <div class="bg-white border-2 border-dashed @if($errors->has('combined_document')) border-red-400 bg-red-50/30 @elseif($currentDocument) border-emerald-400 bg-emerald-50/10 @else border-slate-300 hover:border-emerald-500 @endif rounded-2xl p-6 transition-all">
        <form method="POST" action="{{ route('application.documents.store') }}" enctype="multipart/form-data" id="document-upload-form">
            @csrf
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-900 text-base">Combined Application Documents PDF</span>
                        <span class="text-red-500 font-bold">*</span>
                        <p class="text-xs text-slate-500 mt-0.5">Accepted File Format: .PDF only | Maximum File Size: 10 MB</p>
                    </div>
                    @if($currentDocument)
                        <span class="text-xs bg-emerald-600 text-white px-3 py-1 rounded-full font-bold inline-flex items-center gap-1 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Document Uploaded
                        </span>
                    @endif
                </div>

                @if($errors->has('combined_document'))
                    <div class="p-3 bg-red-100 border border-red-200 text-red-700 text-xs rounded-lg font-medium">
                        {{ $errors->first('combined_document') }}
                    </div>
                @endif

                @if($currentDocument)
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-black text-xs shadow-sm">
                                PDF
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $currentDocument->file_name ?? 'Combined_Application_Documents.pdf' }}</p>
                                <p class="text-[11px] text-slate-500">
                                    Size: {{ round(($currentDocument->file_size ?? 0) / 1024, 1) }} KB | Uploaded: {{ $currentDocument->created_at?->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('application.documents.download', $currentDocument->id) }}" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View PDF Document
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Upload Control -->
                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        {{ $currentDocument ? 'Replace PDF File' : 'Select PDF File from Device' }}
                    </label>

                    <div class="flex items-center gap-3">
                        <label class="cursor-pointer inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>{{ $currentDocument ? 'Choose New PDF File' : 'Browse & Upload PDF' }}</span>
                            <input type="file" name="combined_document" accept=".pdf,application/pdf" class="hidden" onchange="previewAndSubmit(this)">
                        </label>

                        <span id="file-chosen-name" class="text-xs font-semibold text-emerald-800 hidden items-center gap-1.5 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-200">
                            <svg class="w-4 h-4 text-emerald-600 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span id="selected-filename"></span>
                            <span class="text-slate-500 font-normal">(Uploading...)</span>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Navigation Actions -->
    <div class="pt-6 border-t border-slate-200 flex justify-between items-center flex-wrap gap-4">
        <a href="{{ route('application.motivation') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition">
            &larr; Back to Motivation
        </a>

        @if($currentDocument)
            <a href="{{ route('application.review') }}" class="px-8 py-3.5 rounded-xl bg-emerald-700 text-white font-bold text-sm uppercase tracking-wider hover:bg-emerald-800 transition shadow-md inline-flex items-center gap-2">
                <span>Continue to Final Review & Submit</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        @else
            <button type="button" onclick="document.querySelector('#document-upload-form input[type=file]').click()" class="px-8 py-3.5 rounded-xl bg-slate-300 text-slate-600 font-bold text-sm uppercase tracking-wider cursor-pointer hover:bg-slate-400 transition shadow-sm inline-flex items-center gap-2">
                <span>Upload PDF to Continue</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function previewAndSubmit(input) {
        const file = input.files[0];
        if (!file) return;

        // Check if file extension is PDF
        if (!file.name.toLowerCase().endsWith('.pdf') && file.type !== 'application/pdf') {
            alert('Invalid file format. Please select a valid .PDF document.');
            input.value = '';
            return;
        }

        const previewContainer = document.getElementById('file-chosen-name');
        const filenameEl = document.getElementById('selected-filename');
        if (previewContainer && filenameEl) {
            filenameEl.textContent = file.name;
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('inline-flex');
        }

        const form = document.getElementById('document-upload-form');
        if (form) {
            setTimeout(() => form.submit(), 200);
        }
    }
</script>
@endpush
@endsection
