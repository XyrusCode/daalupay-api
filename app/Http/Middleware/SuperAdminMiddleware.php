<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UnauthorizedSuperAdminAccess;
use DaaluPay\Models\Admin;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || $user->role !== 'super_admin') {
            // If user is an admin (but not super_admin), notify super admins
            if ($user && $user->role === 'admin') {
                // Get all super admins
                $superAdmins = Admin::where('role', 'super_admin')->get();

                // Notify each super admin
                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new UnauthorizedSuperAdminAccess(
                        $user,
                        $request->getRequestUri()
                    ));
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Super Admin access required.',
            ], 403);
        }

        return $next($request);
    }
}
