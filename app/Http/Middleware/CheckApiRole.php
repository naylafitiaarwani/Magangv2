<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiRole
{
    /**
     * Role yang diperbolehkan:
     * 1 = Superadmin
     * 2 = Admin
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to continue.',
            ], 401);
        }

        $roleId = (int) ($userData->role_id ?? 0);

        $allowedRoles = array_map('intval', $roles);

        if (!in_array($roleId, $allowedRoles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini.',
            ], 403);
        }

        return $next($request);
    }
}