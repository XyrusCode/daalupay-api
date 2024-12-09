<?php

namespace DaaluPay\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use DaaluPay\Models\ActivityLog;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
              $response = $next($request);

        // if (auth()->check()) {
        //     ActivityLog::create([
        //         'user_id' => auth()->id(),
        //         'action' => $request->method() . ' ' . $request->path(),
        //         'data' => $request->all(),
        //         'ip_address' => $request->ip(),
        //         'user_agent' => $request->userAgent(),
        //         'created_at' => now(),
        //     ]);
        // }

        return $response;
    }
}
