<?php

namespace App\Http\Controllers\API;

use \Firebase\JWT\JWT;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    /**
     * Registrasi akun baru untuk pengunjung website (bukan admin/CMS).
     * Role "Pengunjung" dibuat otomatis kalau belum ada.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $role = Role::firstOrCreate(
            ['name' => 'Pengunjung'],
            ['description' => 'Pengguna umum yang mendaftar melalui website.']
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? '',
            'password' => Hash::make($request->password),
            'image' => '',
            'role_id' => $role->id,
        ]);

        // Langsung login setelah registrasi supaya UX lebih ringkas
        $payload = [
            'iss' => 'member-service',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
            'scope' => 'development',
            'platform' => 'frontend',
            'data' => [
                'id' => $user->id,
                'role_id' => $user->role_id,
                'scope' => 'development',
                'platform' => 'frontend',
            ],
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'  =>  'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }
        $data = User::where('email', $request->email)->first();

        if ($data) {
            // CHECK PASSWORD
            if (Hash::check($request->password, $data->password)) {

                // TOKEN PAYLOAD
                $payload = [
                    'iss' => 'member-service',
                    'sub' => $data->id,
                    'iat' => time(),
                    'exp' => time() + 60 * 60 * 24,
                    'scope' => 'development',
                    'platform' => 'frontend',
                    'data' => [
                        'id' => $data->id,
                        'role_id' => $data->role_id,
                        'scope' => 'development',
                        'platform' => 'frontend',
                    ],
                ];

                $token = JWT::encode(
                    $payload,
                    env('JWT_SECRET'),
                    'HS256'
                );
                return response()->json([
                    'success' => true,
                    'message' => 'Login success',
                    'data' => $data,
                    'token' => $token,
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
            'data' => (object) array(),
        ], 400);
    }

    public function profile(Request $request)
    {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $data = User::find($userData->id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $userId = $userData->id;

        $param = $request->only('email', 'name', 'phone');

        // image handling
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $allowedFileTypes = ['png', 'jpg', 'jpeg'];
            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, $allowedFileTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed. Only png and jpg files are allowed.',
                ], 422);
            }

            $name_original = date('YmdHis') . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploadedFile/image/user'),
                $name_original
            );

            $files = url('uploadedFile/image/user') . '/' . $name_original;

            $param['image'] = $files;
        }

        $updated = User::where('id', $userId)->update($param);

        if ($updated) {
            $user = User::find($userId);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated',
                'data' => $user,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'data' => (object) [],
        ], 500);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Ambil data user dari JWT melalui middleware
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $userId = $userData->id;

        // Ambil data user dari database
        $data = User::find($userId);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Cek password lama
        if (!Hash::check($request->old_password, $data->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid old password',
                'data' => (object) [],
            ], 400);
        }

        // Update password
        $data->password = Hash::make($request->new_password);
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated',
            'data' => $data,
        ], 200);
    }
}