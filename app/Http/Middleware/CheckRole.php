<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Ensure the authenticated user is active and holds one of the allowed roles.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login'));
        }

        if (! $user->is_active) {
            Auth::logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda telah dinonaktifkan.'], 403);
            }

            abort(403, 'Akun Anda telah dinonaktifkan.');
        }

        if (! empty($roles) && ! $user->hasAnyRole(...$roles)) {
            abort(403, 'Anda tidak memiliki hak akses ke modul ini.');
        }

        return $next($request);
    }
}
