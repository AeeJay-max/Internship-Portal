@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <div class="relative py-16 overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); border-bottom: 4px solid #f59e0b;">
        <div class="max-w-7xl mx-auto px-6 text-center text-white relative z-10">
            <p class="text-xs font-extrabold uppercase tracking-widest text-amber-300 mb-2">Government Contact</p>
            <h1 class="text-4xl font-extrabold mb-3 text-white">Contact Ministry</h1>
            <p class="text-sm max-w-2xl mx-auto text-slate-200">
                Get in touch with the Ministry of Sport, Recreation, Arts & Culture regarding internship applications, attachments, and portal support.
            </p>
        </div>
    </div>

    {{-- Main Contact Section --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Contact Info Card --}}
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">Headquarters</span>
                        <h2 class="text-2xl font-extrabold text-slate-900">Ministry Offices</h2>
                    </div>

                    <div class="space-y-4 text-xs text-slate-700">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0 mt-0.5">
                                📍
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-sm">Physical Address</strong>
                                <p>Chinengundu Mashayamombe Building</p>
                                <p>95 Cnr N. Mandela & S. V. Muzenda Street</p>
                                <p>Harare, Zimbabwe</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0 mt-0.5">
                                ✉️
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-sm">Official Email</strong>
                                <p>minofsportandarts@gmail.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0 mt-0.5">
                                📞
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-sm">Phone Number</strong>
                                <p>+263242708345</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0 mt-0.5">
                                🕒
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-sm">Office Hours</strong>
                                <p>Monday – Friday: 07:45 AM – 04:45 PM</p>
                                <p>Weekends & Public Holidays: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inquiry Form --}}
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">Inquiry Form</span>
                        <h2 class="text-2xl font-extrabold text-slate-900">Send an Inquiry</h2>
                    </div>

                    @if(session('success'))
                        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="#" method="POST" class="space-y-4" onsubmit="event.preventDefault(); alert('Thank you for contacting the Ministry. Your message has been received.');">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                            <input type="text" required placeholder="Enter your full name" class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-xs focus:ring-emerald-600 focus:border-emerald-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                            <input type="email" required placeholder="Enter your email address" class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-xs focus:ring-emerald-600 focus:border-emerald-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Subject</label>
                            <input type="text" required placeholder="e.g. Application Attachment Inquiry" class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-xs focus:ring-emerald-600 focus:border-emerald-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Message / Query</label>
                            <textarea rows="4" required placeholder="Type your message here..." class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-xs focus:ring-emerald-600 focus:border-emerald-600"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs uppercase tracking-wider rounded-lg shadow transition">
                            Send Message &rarr;
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection
