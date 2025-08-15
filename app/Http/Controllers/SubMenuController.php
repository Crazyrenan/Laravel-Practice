<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubMenuController extends Controller
{

    public function view()
    {
        // Only menus that allow sub menus (and optionally only the visible ones)
        $menus = DB::select("
            SELECT id, nama_menu
            FROM menus
            WHERE sub_menu = 1 AND status = 1
            ORDER BY position ASC
        ");

        return view('Option.SubMenus', ['menus' => $menus]); // <-- passes $menus to Blade
    }
    // Get all sub menus with parent menu name
    public function index()
    {
        $subMenus = DB::select("
            SELECT sub_menus.*, menus.nama_menu AS parent_menu
            FROM sub_menus
            JOIN menus ON sub_menus.id_menu = menus.id
            ORDER BY sub_menus.position ASC
        ");
        return response()->json($subMenus);
    }

    // Get parent menus (only menus with sub_menu = 1)
    public function getParentMenus()
    {
        $menus = DB::select("SELECT * FROM menus WHERE sub_menu = 1 ORDER BY position ASC");
        return response()->json($menus);
    }

    // Store new sub menu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_menu' => 'required|integer|exists:menus,id',
            'icon' => 'nullable|string',
            'nama' => 'required|string',
            'link' => 'required|string',
            'status' => 'required|boolean',
            'position' => 'required|integer',
        ]);

        DB::insert(
            "INSERT INTO sub_menus (id_menu, icon, nama, link, status, position, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $validated['id_menu'],
                $validated['icon'],
                $validated['nama'],
                $validated['link'],
                $validated['status'],
                $validated['position']
            ]
        );

        return response()->json(['success' => true]);
    }

    // Update sub menu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_menu' => 'required|integer|exists:menus,id',
            'icon' => 'nullable|string',
            'nama' => 'required|string',
            'link' => 'required|string',
            'status' => 'required|boolean',
            'position' => 'required|integer',
        ]);

        DB::update(
            "UPDATE sub_menus SET id_menu = ?, icon = ?, nama = ?, link = ?, status = ?, position = ?, updated_at = NOW()
             WHERE id = ?",
            [
                $validated['id_menu'],
                $validated['icon'],
                $validated['nama'],
                $validated['link'],
                $validated['status'],
                $validated['position'],
                $id
            ]
        );

        return response()->json(['success' => true]);
    }

    // Delete sub menu
    public function destroy($id)
    {
        DB::delete("DELETE FROM sub_menus WHERE id = ?", [$id]);
        DB::statement("ALTER TABLE sub_menus AUTO_INCREMENT = 1");
        return response()->json(['success' => true]);
    }
}
