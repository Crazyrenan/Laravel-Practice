<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class reportController extends Controller
{
    public function totalSpendingByVendor()
    {
       $sql = "
        SELECT vendors.name as vendor_name, vendors.id as vendor_id, 
               SUM(pembelian.grand_total) as total_spending
        FROM pembelian
        JOIN vendors ON pembelian.vendor_id = vendors.id
        GROUP BY vendors.id, vendors.name
        ORDER BY total_spending DESC
        ";
        $data = DB::select($sql);
        return response()->json($data);
    }

    public function monthlySpendingByVendor($vendor_id)
    {
    $sql = "
        SELECT DATE_FORMAT(purchase_date, '%Y-%m') as month, 
               SUM(grand_total) as monthly_total
        FROM pembelian
        WHERE vendor_id = ?
        GROUP BY month
        ORDER BY month
    ";
    $data = DB::select($sql, [$vendor_id]);
    return response()->json($data);
    }


    public function getMonthlyDetail($vendor_id, $month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);

        $details  = DB::select("
            SELECT item_code, item_name, quantity, unit_price, total_price
            FROM pembelian
            WHERE vendor_id = ?
            AND MONTH(purchase_date) = ?
            AND YEAR(purchase_date) = ? ",
            [$vendor_id, $monthNumber, $year]);


            return response()->json($details);



    }
//category report
    public function getQuantityByCategory()
    {
        $data = DB::select("
            SELECT category,SUM(quantity) as total_quantity
            FROM pembelian
            GROUP BY category
            ORDER BY total_quantity DESC");

        return response()->json($data);
    }

    public function getQuantityByCategoryPerMonth()
    {
        $data = DB::select("
            SELECT DATE_FORMAT(purchase_date, '%Y-%m') as month, 
                   category, 
                   SUM(quantity) as total_quantity
            FROM pembelian
            GROUP BY month, category
            ORDER BY month, total_quantity DESC");

        return response()->json($data);
    }

    public function getItemDetailsByMonthAndCategory(Request $request)
    {
        $month = $request->month;
        $category = $request->category;

        $data = DB::select("
            SELECT item_code, item_name,quantity, unit_price, grand_total
            FROM pembelian
            WHERE DATE_FORMAT(purchase_date, '%Y-%m') = ? AND category = ?
        ", [$month, $category]);

        return response()->json($data);
    }



//report status
    public function purchaseReportVendor()
    {
        $vendors = DB::table('vendors')->select('id', 'name')->get();
        print_r($vendors);
        exit;
        return view('Re', compact('vendors'));
    }

    public function getPurchaseReport(Request $request)
    {
        $month = $request->month;
        $project = $request->project;
        $status = $request->status;

        $query = "
            SELECT p.purchase_order_number, pr.name, p.item_name, p.quantity, p.unit, p.buy_price, p.total_price,
                p.tax, p.grand_total, p.purchase_date, p.expected_delivery_date, p.status
            FROM pembelian p
            JOIN projects pr ON p.project_id = pr.id
            WHERE 1=1
        ";

        $parameter = [];

        // Filter by month (YYYY-MM format)
        if (!empty($month)) {
            $query .= " AND DATE_FORMAT(p.purchase_date, '%Y-%m') = ?";
            $parameter[] = $month;
        }

        // Filter by vendor (numeric ID)
        if (!empty($project)) {
            $query .= " AND pr.id = ?";
            $parameter[] = $project;
        }

        // Filter by status
        if (!empty($status)) {
            $query .= " AND p.status = ?";
            $parameter[] = $status;
        }

        $query .= " ORDER BY p.purchase_date DESC";
        $data = DB::select($query, $parameter);

        return response()->json($data);
    }
}