<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnrolledStudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->student()->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'You must be an enrolled student to access the student portal.');
        }

        return $next($request);
    }
}
