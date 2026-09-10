<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Priviledge;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiPrivilege
{
    public function handle(
        Request $request,
        Closure $next,
        int $menuId,
        string $permission
    ): Response {
        // Ambil data user dari JWT
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to continue.',
            ], 401);
        }

        // Ambil role user
        $roleId = (int) ($userData->role_id ?? 0);

        if (!$roleId) {
            return response()->json([
                'success' => false,
                'message' => 'Role user tidak ditemukan.',
            ], 403);
        }

        // Pastikan menu tersedia
        $menu = Menu::withTrashed()->find($menuId);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }

        // Ambil privilege berdasarkan role + menu
        $privilege = Priviledge::where('role_id', $roleId)
            ->where('menu_id', $menuId)
            ->first();

        if (!$privilege) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki privilege untuk mengakses menu ini.',
            ], 403);
        }

        // Permission yang diminta harus tersedia
        $allowedPermissions = [
            'view',
            'add',
            'edit',
            'delete',
            'other',
        ];

        if (!in_array($permission, $allowedPermissions, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis privilege tidak valid.',
            ], 500);
        }

        // Cek privilege
        if ((int) $privilege->{$permission} !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki privilege untuk melakukan tindakan ini.',
            ], 403);
        }

        return $next($request);
    }
}