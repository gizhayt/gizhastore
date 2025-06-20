<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Izinkan akses ke route logout tanpa validasi admin
        // Perhatikan berbagai kemungkinan nama route logout
        if ($request->routeIs('filament.admin.auth.logout') || 
            $request->is('admin/logout') || 
            $request->is('logout')) {
            return $next($request);
        }
        
        // Izinkan juga ke halaman login
        if ($request->routeIs('filament.admin.auth.login') || 
            $request->is('admin/login')) {
            return $next($request);
        }
        
        // Periksa apakah pengguna sudah login dan memiliki peran admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
            }
            
            return redirect()->route('filament.admin.auth.login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}