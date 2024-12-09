<?php

namespace DaaluPay\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyBrowserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = Auth::user();
        $browserId = $request->cookie('browser_id') ?? $request->header('BrowserId');

        // Get SessionID from database
        $session = $this->getSession($user, $browserId);

        if (!$session) {
            return response()->json(['message' => 'Invalid browser ID'], 401);
        }

        // Update session ID in database
        $this->updateSession($user, $browserId, $session->id);

        // Set session ID in the request
        $request->session()->setId($session->id);
        return $next($request);
    }

    private function getSession($user, $browserId):?object {
        // Implement your database query here
        // Example: return Session::where('user_id', $user->id)->where('browser_id', $browserId)->first();
        return null;
    }

    private function updateSession($user, $browserId, $newSessionId): void {
        // Implement your database update here
        // Example: Session::where('user_id', $user->id)->where('browser_id', $browserId)->update(['id' => $newSessionId]);
    }
}
