<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->canAccess($permission)) {
            // If they can access schedules but not dashboard, redirect to schedules index
            if ($permission === 'dashboard' && auth()->user()->canAccess('schedules')) {
                return redirect()->route('schedules.index');
            }
            // If they are a member (only member-dashboard/settings), redirect to dashboard
            if (auth()->user()->canAccess('member-dashboard')) {
                return redirect()->route('dashboard');
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
