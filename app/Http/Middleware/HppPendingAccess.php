<?php

namespace App\Http\Middleware;

use App\Models\LogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HppPendingAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'Admin') {
            $spkId = $request->route('id');
            if ($spkId) {
                $spk = LogService::find($spkId);

                if ($spk && !$spk->hppDetail()->exists()) {
                    if ($request->routeIs('spk.get.hpp', 'spk.store.hpp', 'spk.update.hpp')) {
                        return $next($request);
                    }
                    
                    return redirect('/admin/spk')->with('error', 'Isi HPP terlebih dahulu!');
                }
            }
        }
        return $next($request);
    }
}
