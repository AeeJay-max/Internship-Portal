@extends('layouts.app')

@section('content')

    {{-- Contact Hero Section --}}
    <div class="relative overflow-hidden text-white min-h-[45vh] lg:min-h-[50vh] flex items-center py-12 sm:py-16 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/branding/heroes_acre.jpg') }}'); background-position: center 30%;">
        
        {{-- Semi-Transparent MoSRAC Green Overlay --}}
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(0, 80, 38, 0.90) 0%, rgba(0, 60, 28, 0.88) 55%, rgba(10, 18, 12, 0.92) 100%);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="max-w-2xl text-left space-y-4">
                
                {{-- Institutional Label --}}
                <div class="inline-flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-amber-400 rounded-full"></span>
                    <p class="text-xs sm:text-sm font-bold tracking-widest text-amber-300 uppercase">
                        GOVERNMENT CONTACT & SUPPORT
                    </p>
                </div>

                {{-- Main Heading --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight drop-shadow-sm">
                    Contact Ministry
                </h1>

                {{-- Supporting Description --}}
                <p class="text-base sm:text-lg text-white font-normal leading-relaxed max-w-xl">
                    Get in touch with the Ministry of Sport, Recreation, Arts & Culture regarding internship applications, attachments, and portal support.
                </p>

            </div>
        </div>
    </div>

    {{-- Main Contact Content Section --}}
    <section class="py-12 lg:py-16 bg-slate-100/70 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                {{-- LEFT SIDE: Ministry Offices Information Panel --}}
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-[#005A2B]"></span>
                            <span class="text-xs font-bold text-[#005A2B] uppercase tracking-wider">Headquarters & Location</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                            Ministry Offices
                        </h2>
                    </div>

                    {{-- Institutional Contact List --}}
                    <div class="bg-white border border-slate-200/90 rounded-md p-6 sm:p-7 shadow-sm border-t-4 border-[#005A2B] space-y-5">
                        
                        {{-- Address --}}
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-[#005A2B]/10 text-[#005A2B] flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                                📍
                            </div>
                            <div class="space-y-0.5 text-xs sm:text-sm">
                                <strong class="text-slate-900 block text-sm font-bold uppercase tracking-wide">Physical Address</strong>
                                <p class="text-slate-700 font-medium">Chinengundu Mashayamombe Building</p>
                                <p class="text-slate-600">95 Cnr N. Mandela & S. V. Muzenda Street</p>
                                <p class="text-slate-600">Harare, Zimbabwe</p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="flex items-start gap-4 pt-4 border-t border-slate-100">
                            <div class="w-8 h-8 rounded bg-[#005A2B]/10 text-[#005A2B] flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                                ✉️
                            </div>
                            <div class="space-y-0.5 text-xs sm:text-sm">
                                <strong class="text-slate-900 block text-sm font-bold uppercase tracking-wide">Official Email</strong>
                                <p class="text-slate-700 font-medium">minofsportandarts@gmail.com</p>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="flex items-start gap-4 pt-4 border-t border-slate-100">
                            <div class="w-8 h-8 rounded bg-[#005A2B]/10 text-[#005A2B] flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                                📞
                            </div>
                            <div class="space-y-0.5 text-xs sm:text-sm">
                                <strong class="text-slate-900 block text-sm font-bold uppercase tracking-wide">Phone Number</strong>
                                <p class="text-slate-700 font-medium">+263242708345</p>
                            </div>
                        </div>

                        {{-- Office Hours --}}
                        <div class="flex items-start gap-4 pt-4 border-t border-slate-100">
                            <div class="w-8 h-8 rounded bg-[#005A2B]/10 text-[#005A2B] flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                                🕒
                            </div>
                            <div class="space-y-0.5 text-xs sm:text-sm">
                                <strong class="text-slate-900 block text-sm font-bold uppercase tracking-wide">Office Hours</strong>
                                <p class="text-slate-700 font-medium">Monday – Friday: 07:45 AM – 04:45 PM</p>
                                <p class="text-slate-500 text-xs">Weekends & Public Holidays: Closed</p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RIGHT SIDE: Inquiry Form Container --}}
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Public Support Form</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                            Send an Inquiry
                        </h2>
                    </div>

                    <div class="bg-white border border-slate-200/90 rounded-md p-6 sm:p-8 shadow-sm">
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold rounded-md">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="#" method="POST" class="space-y-4" onsubmit="event.preventDefault(); alert('Thank you for contacting the Ministry. Your message has been received.');">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">Full Name</label>
                                <input type="text" required placeholder="Enter your full name" class="w-full border border-slate-300 rounded-md px-3.5 py-2.5 text-xs sm:text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" required placeholder="Enter your email address" class="w-full border border-slate-300 rounded-md px-3.5 py-2.5 text-xs sm:text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">Subject</label>
                                <input type="text" required placeholder="e.g. Application Attachment Inquiry" class="w-full border border-slate-300 rounded-md px-3.5 py-2.5 text-xs sm:text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">Message / Query</label>
                                <textarea rows="5" required placeholder="Type your message here..." class="w-full border border-slate-300 rounded-md px-3.5 py-2.5 text-xs sm:text-sm focus:ring-[#005A2B] focus:border-[#005A2B]"></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider rounded-md shadow-md transition-all">
                                Send Message &rarr;
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

