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


    
    public function exportAll()
    {
        $sql = "
            SELECT vendors.name as vendor_name,
                DATE_FORMAT(purchase_date, '%Y-%m') as month,
                SUM(grand_total) as monthly_total
            FROM pembelian
            JOIN vendors ON pembelian.vendor_id = vendors.id
            GROUP BY vendors.name, month
            ORDER BY vendors.name, month
        ";
        $data = DB::select($sql);
        return response()->json($data);
    }

    public function getVendorMonthlyDetails(Request $request)
    {
    $vendorId = $request->vendor_id;
    $month = $request->month; // E.g. "Mar"
    $year = $request->year ?? 2024;

    $monthMap = [
        "Jan" => 1, "Feb" => 2, "Mar" => 3, "Apr" => 4,
        "May" => 5, "Jun" => 6, "Jul" => 7, "Aug" => 8,
        "Sep" => 9, "Oct" => 10, "Nov" => 11, "Dec" => 12,
    ];

    $monthNum = $monthMap[$month];

    $details = DB::table('pembelian')
        ->select('item_name', 'quantity', 'unit_price', 'total_price')
        ->where('vendor_id', $vendorId)
        ->whereYear('purchase_date', $year)
        ->whereMonth('purchase_date', $monthNum)
        ->get();

        return response()->json($details);
    }


    


}
