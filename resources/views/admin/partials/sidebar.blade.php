@php
    use App\Models\Application;
    use App\Models\Department;
    use App\Models\InternshipOpportunity;

    $sidebarAwaitingDocs = Application::where('status', Application::STATUS_DOCUMENTS_REQUESTED)->count();
    $sidebarResubmitted  = Application::where('status', Application::STATUS_SUBMITTED)
                               ->where('needs_reupload_review', true)->count();
    $sidebarPendingPlacement = Application::where('status', Application::STATUS_PLACEMENT_PENDING)->count();

    $currentRoute = Route::currentRouteName();

    $isActive = function(string|array $routes) use ($currentRoute): string {
        $routes = is_array($routes) ? $routes : [$routes];
        foreach ($routes as $route) {
            if (str_starts_with($currentRoute ?? '', $route)) {
                return 'bg-blue-50 text-blue-700 border-r-2 border-blue-700';
            }
        }
        return '';
    };
@endphp

<div class="w-56 shrink-0">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">

        {{-- Dashboard link --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-100 hover:bg-slate-50 transition shrink-0 {{ str_starts_with($currentRoute ?? '', 'admin.dashboard') ? 'bg-blue-50 text-blue-900 border-r-2 border-blue-900 font-bold' : 'text-slate-600' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm font-semibold">Admin Dashboard</span>
        </a>

        <div class="overflow-y-auto flex-1">
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-2">Internship Management</p>
            </div>

            {{-- Applications --}}
            <a href="{{ route('admin.applications.index') }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.applications']) }}">
                <span class="text-slate-400 group-hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900 flex-1">Applications</span>
                @if($sidebarResubmitted > 0)
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ $sidebarResubmitted }}</span>
                @endif
            </a>

            {{-- Placement Assignments --}}
            <a href="{{ route('admin.placements.index') }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.placements']) }}">
                <span class="text-slate-400 group-hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900 flex-1">Placements</span>
                @if($sidebarPendingPlacement > 0)
                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ $sidebarPendingPlacement }}</span>
                @endif
            </a>

            {{-- Departments --}}
            <a href="{{ route('admin.departments.index') }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.departments']) }}">
                <span class="text-slate-400 group-hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900">Departments</span>
            </a>

            {{-- Opportunities --}}
            <a href="{{ route('admin.opportunities.index') }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.opportunities']) }}">
                <span class="text-slate-400 group-hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900">Opportunities</span>
            </a>

            {{-- Applicants CRM --}}
            <a href="{{ route('admin.applicants.index') }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.applicants']) }}">
                <span class="text-slate-400 group-hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900">Applicants</span>
            </a>

            {{-- News --}}
            <a href="{{ route('admin.news.index') }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.news']) }}">
                <span class="text-slate-400 group-hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900">Ministry News</span>
            </a>

            @if(Auth::user()->isSuperAdmin())
                <div class="px-4 pt-4 pb-1 border-t border-slate-100 mt-2">
                    <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-2">Super Admin</p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 hover:text-blue-900 transition group {{ $isActive(['admin.users']) }}">
                    <span class="text-slate-400 group-hover:text-blue-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900">Admin Users</span>
                </a>
            @endif
        </div>
    </div>
</div>
