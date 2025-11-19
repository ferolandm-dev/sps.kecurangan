<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 🔹 Tampilkan semua user
     */
    public function index()
    {
        $users = DB::table('users')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * 🔹 Form tambah user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 🔹 Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        DB::table('users')->insert([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * 🔹 Form edit user
     */
    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan.');
        }

        // ❗ Proteksi: hanya Admin (ID=1) yang boleh mengedit user Admin
        if ($id == 1 && auth()->id() != 1) {
            return redirect()->route('user.index')->with('error', 'Hanya Admin yang dapat mengedit user ini.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * 🔹 Update user
     */
    public function update(Request $request, $id)
    {
        // ❗ Proteksi: hanya Admin (ID=1) yang boleh update user Admin
        if ($id == 1 && auth()->id() != 1) {
            return redirect()->route('user.index')->with('error', 'Anda tidak diizinkan mengupdate user Admin.');
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * 🔹 Hapus user
     */
    public function destroy($id)
    {
        // ❗ Proteksi: tidak boleh hapus Admin kecuali Admin sendiri
        if ($id == 1 && auth()->id() != 1) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus user Admin.');
        }

        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * 🔹 Halaman pengaturan akses user
     */
    public function access($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan.');
        }

        // ❗ Proteksi: tidak boleh atur akses Admin, kecuali Admin sendiri
        if ($id == 1 && auth()->id() != 1) {
            return redirect()->route('user.index')->with('error', 'Anda tidak diizinkan mengatur akses user Admin.');
        }

        $menus = DB::table('menus')
            ->orderBy('main_menu')
            ->orderBy('order')
            ->get()
            ->groupBy(fn($menu) => $menu->main_menu ?? '');

        $userAccess = DB::table('user_access')
            ->where('user_id', $id)
            ->get()
            ->groupBy(fn($acc) => $acc->main_menu ?? '');

        return view('users.access', compact('user', 'menus', 'userAccess'));
    }

    /**
     * 🔹 Update akses user
     */
    public function updateAccess(Request $request, $id)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $targetUser = DB::table('users')->where('id', $id)->first();
        if (!$targetUser) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan.');
        }

        // ❗ Proteksi: tidak boleh ubah akses Admin kecuali Admin sendiri
        if ($id == 1 && auth()->id() != 1) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat mengubah akses user Admin.');
        }

        // Hapus semua akses lama user
        DB::table('user_access')->where('user_id', $id)->delete();

        // Simpan akses baru
        if ($request->filled('access')) {
            foreach ($request->access as $mainMenu => $subMenus) {
                foreach ($subMenus as $subMenu => $permissions) {

                    if (strtolower($subMenu) === 'user profile') continue;

                    $isMainOnly = $subMenu === '_main_';

                    DB::table('user_access')->insert([
                        'user_id'     => $id,
                        'main_menu'   => $mainMenu === 'root' ? '' : $mainMenu,
                        'sub_menu'    => $isMainOnly ? null : ($subMenu === 'null' ? null : $subMenu),
                        'can_access'  => $permissions['can_access'] ?? 0,
                        'can_create'  => $permissions['can_create'] ?? 0,
                        'can_edit'    => $permissions['can_edit'] ?? 0,
                        'can_delete'  => $permissions['can_delete'] ?? 0,
                        'can_print'   => $permissions['can_print'] ?? 0,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }
        }

        return redirect()->route('user.index')->with('success', 'Akses pengguna berhasil diperbarui!');
    }

}
