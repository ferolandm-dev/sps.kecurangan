<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('checkAccess')) {
    /**
     * Cek hak akses user berdasarkan menu dan tipe aksi
     *
     * @param string $mainMenu
     * @param string|null $subMenu
     * @param string $type ('access', 'create', 'edit', 'delete', 'print')
     * @return bool
     */
    function checkAccess($mainMenu, $subMenu = null, $type = 'access')
    {
        if (!auth()->check()) {
            return false;
        }

        $access = DB::table('user_access')
            ->where('user_id', auth()->id())
            ->where('main_menu', $mainMenu)
            ->where('sub_menu', $subMenu)
            ->first();

        if (!$access) {
            return false;
        }

        switch ($type) {
            case 'create':
                return (bool) $access->can_create;
            case 'edit':
                return (bool) $access->can_edit;
            case 'delete':
                return (bool) $access->can_delete;
            case 'print': // ✅ Tambahan baru
                return (bool) $access->can_print;
            default:
                return (bool) $access->can_access;
        }
    }
}
