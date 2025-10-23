<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage examples:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,lecturer')
     *   ->middleware('role:admin|lecturer')
     *   ->middleware('role:admin','lecturer')  // variadic
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // If user not authenticated, redirect to login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Normalize roles into flat array of lowercase role strings
        $allowedRoles = $this->normalizeRoles($roles);

        // If wildcard present, allow any authenticated user
        if (in_array('*', $allowedRoles, true)) {
            return $this->safeNext($request, $next);
        }

        // If user provides a hasRole method (package or custom), prefer it.
        if (method_exists($user, 'hasRole')) {
            try {
                // pass array — method may accept string or array
                if ($user->hasRole($allowedRoles)) {
                    return $this->safeNext($request, $next);
                }
            } catch (\Throwable $e) {
                // Log and fallback to role property comparison
                Log::warning('RoleMiddleware: hasRole() threw exception, falling back to role property. ' . $e->getMessage(), [
                    'user_id' => $user->id ?? null,
                    'roles_checked' => $allowedRoles,
                ]);
            }
        }

        // Fallback: compare $user->role (string) case-insensitively against allowed roles.
        $userRole = strtolower((string) ($user->role ?? ''));

        foreach ($allowedRoles as $role) {
            if ($userRole !== '' && strcasecmp($userRole, strtolower($role)) === 0) {
                return $this->safeNext($request, $next);
            }
        }

        // Not allowed: return 403 Forbidden
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized. Required role: ' . implode(', ', $allowedRoles));
    }

    /**
     * Wrapper for $next($request) that catches BindingResolutionException
     * and logs details for easier debugging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected function safeNext(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (BindingResolutionException $e) {
            Log::error('RoleMiddleware - BindingResolutionException when resolving downstream controller', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'route' => optional($request->route())->getName(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Inform user / developer with clear 500 message
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Server configuration error: required class not found. Run composer dump-autoload and verify controller files.');
        }
    }

    /**
     * Normalize incoming roles argument(s) into a flat, unique array.
     *
     * Accepts:
     *  - variadic parameters: role:admin,lecturer
     *  - pipe separated: role:admin|lecturer
     *  - multiple middleware args: ->middleware('role:admin','lecturer')
     *
     * @param  array  $roles
     * @return array
     */
    protected function normalizeRoles(array $roles): array
    {
        $out = [];

        foreach ($roles as $r) {
            if (is_array($r)) {
                $out = array_merge($out, $this->normalizeRoles($r));
                continue;
            }

            // allow passing null/empty: skip
            if ($r === null || $r === '') {
                continue;
            }

            // split by comma or pipe
            $parts = preg_split('/[,\|]/', (string) $r);

            foreach ($parts as $p) {
                $p = trim((string) $p);
                if ($p === '') {
                    continue;
                }
                // normalize lower-case; preserve '*' wildcard
                $out[] = $p === '*' ? '*' : strtolower($p);
            }
        }

        // unique and reindex
        return array_values(array_unique($out));
    }
}
