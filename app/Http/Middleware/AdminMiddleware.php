<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login DAN apakah dia seorang admin.
        // Beberapa pengguna menggunakan role='admin', beberapa menggunakan is_admin=1.
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->is_admin == 1)) {
            return $next($request);
        }

        // Kalau bukan admin, tendang ke dashboard biasa
        return redirect('/dashboard');
    }
}