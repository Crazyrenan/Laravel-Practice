<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiValidation;

class MenuSubmenuController extends Controller
{

    // Show merged menus & submenus
    public function viewMerged()
    {
        $menus = DB::table('menus')->orderBy('position')->get();
        $submenus = DB::table('sub_menus')->get();
        return view('Option.Menus_Submenus', compact('menus', 'submenus'));
    }

    // Get menus for AJAX
    public function index()
    {
        $menus = DB::table('menus')->orderBy('position')->get();
        $submenus = DB::table('sub_menus')->get();
        
        return response()->json([
            'menus' => $menus,
            'submenus' => $submenus,
        ]);
    }

     use ApiValidation;
    // Store Menu
    public function storeMenu(Request $request)
    {
        $data = $request->validate([
            'icon' => 'required|string',
            'nama_menu' => 'required|string',
            'link' => 'nullable|string',
            'status' => 'required|boolean',
            'sub_menu' => 'nullable|boolean',
            'position' => 'required|integer',
        ]);

        DB::insert(
            "INSERT INTO menus (icon, nama_menu, link, status, sub_menu, position) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['icon'],
                $data['nama_menu'],
                $data['link'],
                $data['status'],
                $data['sub_menu'],
                $data['position']
            ]
        );

        return response()->json(['success' => true]);
    }

    // Update Menu
    public function updateMenu(Request $request, $id)
    {
        $data = $request->validate([
            'icon' => 'required|string',
            'nama_menu' => 'required|string',
            'link' => 'nullable|string',
            'status' => 'required|boolean',
            'sub_menu' => 'nullable|boolean',
            'position' => 'required|integer',
        ]);

        DB::update(
            "UPDATE menus SET icon = ?, nama_menu = ?, link = ?, status = ?, sub_menu = ?, position = ? WHERE id = ?",
            [
                $data['icon'],
                $data['nama_menu'],
                $data['link'],
                $data['status'],
                $data['sub_menu'],
                $data['position'],
                $id
            ]
        );

        return response()->json(['success' => true]);
    }

    // Delete Menu
    public function destroyMenu($id)
    {
        DB::delete("DELETE FROM menus WHERE id = ?", [$id]);
        DB::statement("ALTER TABLE menus AUTO_INCREMENT = 1");
        return response()->json(['success' => true]);
    }

    // Store Submenu
    public function storeSubMenu(Request $request)
    {
        $data = $request->validate([
            'id_menu' => 'required|exists:menus,id',
            'icon' => 'nullable|string',
            'nama' => 'required|string',
            'link' => 'nullable|string',
            'status' => 'required|boolean',
            'position' => 'required|integer',
        ]);

        DB::insert(
            "INSERT INTO sub_menus (id_menu, icon, nama, link, status, position) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['id_menu'],
                $data['icon'],
                $data['nama'],
                $data['link'],
                $data['status'],
                $data['position']
            ]
        );

        return response()->json(['success' => true]);
    }

    // Update Submenu
    public function updateSubMenu(Request $request, $id)
    {
        $data = $request->validate([
            'id_menu' => 'required|exists:menus,id',
            'icon' => 'nullable|string',
            'nama' => 'required|string',
            'link' => 'nullable|string',
            'status' => 'required|boolean',
            'position' => 'required|integer',
        ]);

        DB::update(
            "UPDATE sub_menus SET id_menu = ?, icon = ?, nama = ?, link = ?, status = ?, position = ? WHERE id = ?",
            [
                $data['id_menu'],
                $data['icon'],
                $data['nama'],
                $data['link'],
                $data['status'],
                $data['position'],
                $id
            ]
        );

        return response()->json(['success' => true]);
    }

    // Delete Submenu
    public function destroySubMenu($id)
    {
        DB::delete("DELETE FROM sub_menus WHERE id = ?", [$id]);
        return response()->json(['success' => true]);
    }
}
