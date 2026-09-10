<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar user.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search berdasarkan nama atau email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil.',
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ], 200);
    }

    /**
     * Menampilkan detail user.
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail user berhasil diambil.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ], 200);
    }

    /**
     * Membuat user baru.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at,
            ],
        ], 201);
    }

    /**
     * Mengubah user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ) {
        $data = $request->validated();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? '';
        $user->role_id = $data['role_id'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
                'updated_at' => $user->updated_at,
            ],
        ], 200);
    }

    /**
     * Menghapus user.
     */
    public function destroy(
        Request $request,
        User $user
    ) {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        // Tidak boleh menghapus akun sendiri
        if ((int) $userData->id === (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri.',
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
            'data' => [
                'id' => $user->id,
            ],
        ], 200);
    }
}