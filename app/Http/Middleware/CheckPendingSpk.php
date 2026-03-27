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
        if (auth()->check()) {

            if (auth()->id() == 8) {
                $hasPending = LogService::where('status', 'menunggu')->exists();

                if ($hasPending && !$request->is('data-spk*') && !$request->is('spk*')) {
                    return redirect('/data-spk')->with(
                        'warning',
                        'Anda harus menyelesaikan SPK yang masih menunggu!'
                    );
                }
            }
        }
        return $next($request);
    }
}
