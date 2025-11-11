<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Tampilkan daftar menu.
     */
    public function index()
    {
        $menus = DB::table('menus')
            ->select('id', 'main_menu', 'sub_menu', 'icon', 'route', 'main_order', 'order', 'can_crud', 'can_print')
            ->orderBy('main_order')  // Urut berdasarkan main menu
            ->orderBy('order')       // Urut submenu di dalam main menu
            ->get()
            ->groupBy('main_menu');

        return view('menus.index', compact('menus'));
    }

    /**
     * Tampilkan halaman tambah menu.
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * Simpan menu baru ke database.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'main_menu'  => 'required|string|max:100',
        'sub_menu'   => 'nullable|string|max:100',
        'icon'       => 'nullable|string|max:255',
        'route'      => 'nullable|string|max:255',
        'main_order' => 'required|integer|min:0',
        'order'      => 'nullable|integer|min:0',
        'can_crud'   => 'nullable|boolean',
        'can_print'  => 'nullable|boolean',
    ]);

    try {
        // 🔹 Cek: apakah ada menu lain dengan main_menu & order yang sama
        $exists = DB::table('menus')
            ->where('main_menu', $request->main_menu)
            ->where('order', $request->order)
            ->where('main_order', $request->main_order)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Urutan submenu {$request->order} sudah digunakan di menu utama {$request->main_menu}.");
        }

        DB::table('menus')->insert([
            'main_menu'  => $request->main_menu,
            'sub_menu'   => $request->sub_menu,
            'icon'       => $request->icon,
            'route'      => $request->route,
            'main_order' => $request->main_order,
            'order'      => $request->order ?? 0,
            'can_crud'   => $request->can_crud ?? 0,
            'can_print'  => $request->can_print ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}




    /**
     * Tampilkan halaman edit menu.
     */
    public function edit($id)
    {
        $menu = DB::table('menus')->where('id', $id)->first();

        if (!$menu) {
            return redirect()->route('menus.index')->with('error', 'Menu tidak ditemukan.');
        }

        return view('menus.edit', compact('menu'));
    }

    /**
     * Update data menu di database.
     */
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'main_menu'  => 'required|string|max:100',
        'sub_menu'   => 'nullable|string|max:100',
        'icon'       => 'nullable|string|max:255',
        'route'      => 'nullable|string|max:255',
        'main_order' => 'required|integer|min:0',
        'order'      => 'nullable|integer|min:0',
        'can_crud'   => 'nullable|boolean',
        'can_print'  => 'nullable|boolean',
    ]);

    try {
        // 🔹 Cek duplikat submenu di main_menu & main_order yang sama
        $exists = DB::table('menus')
            ->where('main_menu', $request->main_menu)
            ->where('main_order', $request->main_order)
            ->where('order', $request->order)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Urutan submenu {$request->order} sudah digunakan di menu utama {$request->main_menu}.");
        }

        DB::table('menus')
            ->where('id', $id)
            ->update([
                'main_menu'  => $request->main_menu,
                'sub_menu'   => $request->sub_menu,
                'icon'       => $request->icon,
                'route'      => $request->route,
                'main_order' => $request->main_order,
                'order'      => $request->order ?? 0,
                'can_crud'   => $request->can_crud ?? 0,
                'can_print'  => $request->can_print ?? 0,
                'updated_at' => now(),
            ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
     * Hapus menu dari database.
     */
    public function destroy($id)
    {
        DB::table('menus')->where('id', $id)->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}