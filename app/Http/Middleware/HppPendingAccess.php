<?php

namespace App\Http\Middleware;

use App\Models\LogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class HppPendingAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $hppRequiredUsers = [22, 18];

        if ($user && in_array($user->id, $hppRequiredUsers)) {

            // Batas H+1
            $lockDate = Carbon::today()->subDays(2);

            $spkPending = LogService::where('status', LogService::STATUS_SELESAI)
                ->whereDate('tanggal', '>=', '2026-04-01')
                ->whereDate('tanggal', '<=', $lockDate)
                ->whereDoesntHave('hppDetail')
                ->exists();
                
            if ($spkPending) {

                // route yang tetap boleh diakses
                if (
                    $request->routeIs(
                        'admin.spk',
                        'spk.data',
                        'spk.detail',
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