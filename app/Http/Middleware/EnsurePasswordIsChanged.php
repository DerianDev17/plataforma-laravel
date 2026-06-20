<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordIsChanged
{
    /**
     * Rutas permitidas aunque el usuario deba cambiar su contraseña.
     */
    protected array $allowedPatterns = [
        'profile.show',
        'user-password.update',
        'logout',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || ! $user->must_change_password) {
            return $next($request);
        }

        if ($request->is('livewire/*')) {
            return $next($request);
        }

        if ($request->routeIs($this->allowedPatterns)) {
            return $next($request);
        }

        return redirect()->route('profile.show');
    }
}
