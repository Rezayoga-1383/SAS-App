<?php

namespace App\Http\Middleware;

use App\Models\LogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPendingSpk
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->id() == 8) {

            $hasPending = LogService::where('status', 'menunggu')->exists();

            if ($hasPending && !$request->ajax()) {

                $allowedPaths = [
                    'data-spk',
                    'spk/*',
                    'check-pending-spk',
                    'absensi/karyawan',
                    'absensi/karyawan/store',
                ];

                $allowed = false;

                foreach ($allowedPaths as $path) {
                    if ($request->is($path)) {
                        $allowed = true;
                        break;
                    }
                }

                if (!$allowed) {
                    return redirect()
                        ->route('spk.index')
                        ->with(
                            'warning',
                            'Masih terdapat SPK yang berstatus menunggu. Silahkan selesaikan SPK terlebih dahulu'
                        );
                }
            }
            
        }
        return $next($request);
    }
}
