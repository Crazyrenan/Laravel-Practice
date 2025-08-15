<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\SubMenu;

class MenuController extends Controller
{

    public function view()
    {
        return view('Option.Menus'); 
    }

    public function index()
    {
        $menus = DB::select("SELECT * from menus ORDER BY position ASC");
        return response()->json($menus);
    }


    public function store(Request $request)
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
    
    public function update(Request $request, $id)
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


    public function destroy($id)
    {
        DB::delete("DELETE FROM menus WHERE id = ?", [$id]);
        DB::statement("ALTER TABLE menus AUTO_INCREMENT = 1");
        return response()->json(['success' => true]);
    }
    
}
