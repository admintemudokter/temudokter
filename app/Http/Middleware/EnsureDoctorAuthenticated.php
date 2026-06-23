<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoctorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('doctor')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('doctor.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
