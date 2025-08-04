<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class userController extends Controller
{
    // 1. Pembelian Master View
   public function pembelianMasterView()
  {
        $vendors = DB::table('vendors')->get();
        $projects = DB::table('projects')->get();
        $requests = DB::table('request')->get();

        dd($projects->first()); // 🔍 check what's inside

        return view('Master.pembelianmaster', compact('vendors', 'projects', 'requests'));
    }


    // 2. Project Master View
    public function projectMasterView()
    {
        return view('projectmaster');
    }

    // 3. Request Master View
    public function requestMasterView()
    {
        return view('requestmaster');
    }

    // 4. Vendor Master View
    public function vendorMasterView()
    {
        return view('vendormaster');
    }

    public function insertpembelian(Request $request)
    {
        DB::table('pembelian')->insert([
            'vendor_id' => $request->vendor_id,
            'project_id' => $request->project_id,
            'requested_by' => $request->requested_by,
            'purchase_order_number' => $request->purchase_order_number,
            'item_name' => $request->item_name,
            'item_code' => $request->item_code,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'buy_price' => $request->buy_price,
            'unit_price' => $request->unit_price,
            'total_price' => $request->unit_price * $request->quantity,
            'tax' => $request->tax,
            'grand_total' => ($request->unit_price * $request->quantity) + $request->tax,
            'purchase_date' => $request->purchase_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Inserted']);
        
    }


    public function deletePembelian(Request $request)
    {
    $ids = $request->input('ids');
    if (is_array($ids) && count($ids) > 0) {
        DB::table('pembelian')->whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Selected rows deleted.']);
    }
    return response()->json(['message' => 'No IDs provided.'], 400);
    }

}
