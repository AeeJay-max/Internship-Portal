@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-12">

        {{-- Header --}}
        <div class="mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full overflow-hidden shadow-lg shrink-0">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white text-2xl font-bold bg-[#005A2B]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">
                        {{ Auth::user()->name }}
                    </h1>
                    <p class="text-sm text-slate-500 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            {{-- Profile Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-[#005A2B]">
                        <svg class="w-4 h-4 text-[#005A2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900">Profile Information</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Update your name and email address</p>
                    </div>
                </div>
                <div class="p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-[#005A2B]">
                        <svg class="w-4 h-4 text-[#005A2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800">Update Password</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Use a long, random password to stay secure</p>
                    </div>
                </div>
                <div class="p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-red-100 flex items-center gap-3" style="background: #fff8f8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-red-700">Delete Account</h2>
                        <p class="text-xs text-red-400 mt-0.5">Permanently delete your account and all data</p>
                    </div>
                </div>
                <div class="p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
@endsection
