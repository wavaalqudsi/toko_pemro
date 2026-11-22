<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasStoreMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::user()->store) {
            return redirect()->route('penjual.toko.create')
                ->with('error', 'Silakan buat toko terlebih dahulu sebelum mulai berjualan.');
        }

        return $next($request);
    }
}
