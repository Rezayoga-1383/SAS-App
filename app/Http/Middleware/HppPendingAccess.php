<?php

namespace App\Http\Middleware;

use App\Models\LogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HppPendingAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->id == 22) {

            $spkPending = LogService::where('status', LogService::STATUS_SELESAI)
                ->whereDate('tanggal', '>=', '2026-04-01') // 🔥 BATAS TANGGAL
                ->whereDoesntHave('hppDetail')
                ->exists();

            if ($spkPending) {

                // route yang tetap boleh diakses
                if (
                    $request->routeIs(
                        'admin.spk',
                        'spk.data',
                        'spk.get.hpp',
                        'spk.store.hpp',
                        'spk.update.hpp'
                    )
                ) {
                    return $next($request);
                }

                return redirect()
                    ->route('admin.spk')
                    ->with('hpp_required', true);
            }
        }

        return $next($request);
    }
}