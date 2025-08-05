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


    //reporting 

    public function reportByVendor(Request $request)
    {
        $sql = "
        SELECT v.name AS vendor_name,
            COUNT(p.id) AS total_orders,
            SUM(p.total_price) AS total_amount 
        FROM pembelian p
        LEFT JOIN vendors v ON p.vendor_id = v.id
        WHERE (:vendor_id IS NULL OR p.vendor_id = :vendor_id
        GROUP BY p.vendor_id, v.name
        ORDER BY total_orders DESC
    ";

        $data = DB::select($sql, [
            'vendor_id' => $request->input('vendor_id', null),
        ]);

        $vendors = DB::select('SELECT id, name FROM vendors');

        return view('report.vendor', [
            'data' => $data,
            'vendors' => collect($vendors)->pluck('name', 'id'),
        ]);
    }

    public function reportByProject(Request $request)
    {
        $sql = "
        SELECT p.name AS project_name,
            COUNT(b.id) AS total_orders,
            SUM(b.total_price) AS total_amount 
        FROM pembelian b
        LEFT JOIN projects p ON b.project_id = p.id
        WHERE (:project_id IS NULL OR b.project_id = :project_id)
        GROUP BY b.project_id, p.name
        ORDER BY total_orders DESC
    ";
        $data = DB::select($sql, [
            'project_id' => $request->input('project_id', null),
        ]);

        $projects = DB::select('SELECT id, name FROM projects');
        
        return view('report.project', [
            'data' => $data,
            'projects' => collect($projects)->pluck('name', 'id'),
        ]);


    }

    public function reportByMonth(Request $request)
    {
        $sql = "
        SELECT MONTH(p.purchase_date) AS month,
            COUNT(p.id) AS total_orders,
            SUM(p.total_price) AS total_amount
        FROM pembelian p
        GROUP BY MONTH(p.purchase_date)
        ORDER BY month DESC
    ";
        $data = DB::select($sql);

       return view('report.month', compact('data'));

    }

    public function reportByCategory(Request $request)
    {
        $sql = "
        SELECT p.category AS category,
            COUNT(p.id) AS total_orders,
            SUM(p.total_price) AS total_amount
        FROM pembelian p
        GROUP BY p.category
        ORDER BY total_orders DESC
        ";

        $data = DB::select($sql);

        return view('report.category', compact('data'));
    }

    public function reportByRequest(Request $request)
    {
        $sql = "
            SELECT r.name AS request_name,
                COUNT(p.id) AS total_orders,
                SUM(p.total_price) AS total_amount
            FROM pembelian p
            LEFT JOIN request r ON p.requested_by = r.id
            WHERE (:requested_by IS NULL OR p.requested_by = :requested_by)
            GROUP BY p.requested_by, r.name
            ORDER BY total_orders DESC
        ";

        $data = DB::select($sql, [
            'requested_by' => $request->input('requested_by', null),
        ]);

        $requests = DB::select('SELECT id, name FROM request');

        return view('report.request', [
            'data' => $data,
            'requests' => collect($requests)->pluck('name', 'id'),
        ]);
    }


   public function masterReport()
    {
        $vendorData = DB::select("
            SELECT v.name AS vendor_name,
                COUNT(p.id) AS total_orders,
                SUM(p.total_price) AS total_amount
            FROM pembelian p
            LEFT JOIN vendors v ON p.vendor_id = v.id
            GROUP BY p.vendor_id, v.name
        ");

        $projectData = DB::select("
            SELECT pr.name AS project_name,
                COUNT(p.id) AS total_orders,
                SUM(p.total_price) AS total_amount
            FROM pembelian p
            LEFT JOIN projects pr ON p.project_id = pr.id
            GROUP BY p.project_id, pr.name
        ");

        $monthData = DB::select("
            SELECT MONTH(purchase_date) as month,
                COUNT(id) as total_orders,
                SUM(total_price) as total_amount
            FROM pembelian
            GROUP BY MONTH(purchase_date)
        ");

        $categoryData = DB::select("
            SELECT category,
                COUNT(*) as total_orders,
                SUM(total_price) as total_amount
            FROM pembelian
            GROUP BY category
        ");

        $requestData = DB::select("
            SELECT r.name AS request_name,
                COUNT(p.id) AS total_orders,
                SUM(p.total_price) AS total_amount
            FROM pembelian p
            LEFT JOIN request r ON p.requested_by = r.id
            GROUP BY p.requested_by, r.name
        ");

        return view('master.report', compact(
            'vendorData',
            'projectData',
            'monthData',
            'categoryData',
            'requestData'
        ));
    }

}