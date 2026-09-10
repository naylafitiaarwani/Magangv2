<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Menu;
use App\Models\Priviledge;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRoleController extends Controller
{
    /**
     * Menampilkan daftar role.
     */
    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Data role berhasil diambil.',
            'data' => $roles->items(),
            'pagination' => [
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
            ],
        ], 200);
    }

    /**
     * Menampilkan detail role beserta privilege.
     */
    public function show(Role $role)
    {
        $role->load([
            'priviledge.menu'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail role berhasil diambil.',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,

                'privileges' => $role->priviledge->map(function ($privilege) {
                    return [
                        'id' => $privilege->id,
                        'menu_id' => $privilege->menu_id,
                        'menu_name' => $privilege->menu
                            ? $privilege->menu->name
                            : null,
                        'view' => (int) $privilege->view,
                        'add' => (int) $privilege->add,
                        'edit' => (int) $privilege->edit,
                        'delete' => (int) $privilege->delete,
                        'other' => (int) $privilege->other,
                    ];
                })->values(),
            ],
        ], 200);
    }

    /**
     * Membuat role baru.
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            $privileges = $data['privileges'] ?? [];

            foreach ($privileges as $privilegeData) {
                Priviledge::create([
                    'role_id' => $role->id,
                    'menu_id' => $privilegeData['menu_id'],
                    'view' => $privilegeData['view'] ?? 0,
                    'add' => $privilegeData['add'] ?? 0,
                    'edit' => $privilegeData['edit'] ?? 0,
                    'delete' => $privilegeData['delete'] ?? 0,
                    'other' => $privilegeData['other'] ?? 0,
                ]);
            }

            DB::commit();

            $role->load('priviledge');

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dibuat.',
                'data' => $role,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengubah role dan privilege.
     */
    public function update(
        UpdateRoleRequest $request,
        Role $role
    ) {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $role->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            if (array_key_exists('privileges', $data)) {

                foreach ($data['privileges'] as $privilegeData) {

                    Priviledge::updateOrCreate(
                        [
                            'role_id' => $role->id,
                            'menu_id' => $privilegeData['menu_id'],
                        ],
                        [
                            'view' => $privilegeData['view'] ?? 0,
                            'add' => $privilegeData['add'] ?? 0,
                            'edit' => $privilegeData['edit'] ?? 0,
                            'delete' => $privilegeData['delete'] ?? 0,
                            'other' => $privilegeData['other'] ?? 0,
                        ]
                    );
                }
            }

            DB::commit();

            $role->load('priviledge');

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui.',
                'data' => $role,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghapus role.
     */
    public function destroy(Role $role)
    {
        if ($role->id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Role Super Admin tidak dapat dihapus.',
            ], 400);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak dapat dihapus karena masih digunakan oleh user.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            Priviledge::where('role_id', $role->id)->delete();

            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.',
                'data' => [
                    'id' => $role->id,
                ],
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}