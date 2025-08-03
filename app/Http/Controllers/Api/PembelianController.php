<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\pembelian;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use function Laravel\Prompts\table;

class PembelianController extends Controller
{
    public function statusCount(): JsonResponse
    {
        $pending = pembelian::where('status', 'Pending')->count();
        $approved = pembelian::where('status', 'Approved')->count();
        $delivered = pembelian::where('status', 'Delivered')->count();

        return response()->json([
            'Pending' => $pending,
            'Approved' => $approved,
            'Delivered' => $delivered
        ]);
    }
    

    public function status2()
    {
    $query = "
        SELECT status, COUNT(*) AS total 
        FROM pembelian 
        WHERE status IN ('Pending', 'Approved', 'Delivered') 
        GROUP BY status
    ";

    $results = DB::select($query);

    $statusCounts = [];
    foreach ($results as $row) {
        $statusCounts[$row->status] = $row->total;
    }

    return response()->json($statusCounts);
    }   

    public function getStatusDetail($status)
    {   
    $data = Pembelian::where('status', $status)->get(['id', 'item_code','purchase_date', 'expected_delivery_date', 'status']);

    return response()->json($data);
    }

    public function mostOrdersMonth(): JsonResponse
    {
        $result = Pembelian::selectRaw('MONTH(purchase_date) as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderByDesc('month')
        ->Get();

        return response()->json($result);
    }

    public function OrderMonth2()
    {
        $month2 = "
        SELECT MONTH(purchase_date) AS month, COUNT(*) AS total 
        FROM pembelian
        GROUP BY MONTH(purchase_date)
        ORDER BY month DESC
        ";

        $monthbaru = DB::select($month2);

        return response()->json($monthbaru);

    }
    public function DetailsByMonth($month)
    {
    $items = Pembelian::whereMonth('purchase_date', $month)->get(['item_name', 'item_code', 'purchase_date']);
    return response()->json($items);
    }


    public function TotalVendor()
    {
        $data = DB::table('pembelian')
            ->select('vendor_id', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('vendor_id')
            ->orderByDesc('total_orders')
            ->take(5)
            ->get();

        return response()->json($data);
    }

    public function TotalVendor2()
    {
        $vendor2 ="
        SELECT vendor_id, COUNT(*) AS total
        FROM pembelian
        GROUP BY vendor_id
        ORDER BY total DESC
        ";

        $vendorbaru = DB::select($vendor2);

        return response()->json($vendorbaru);
    }

    public function DetailByVendor($vendor_id, Request $request)
    {
    $start = $request->query('start_date');
    $end = $request->query('end_date');

    $sql = "
        SELECT vendor_id, purchase_order_number, item_name, item_code, category, quantity
        FROM pembelian
        WHERE vendor_id = ?
    ";

    $bindings = [$vendor_id];

    if ($start) {
        $sql .= " AND DATE(purchase_date) >= ?";
        $bindings[] = $start;
    }

    if ($end) {
        $sql .= " AND DATE(purchase_date) <= ?";
        $bindings[] = $end;
    }

    $details = DB::select($sql, $bindings);
    
    return response()->json($details);
    }

    public function VendorByRange(Request $request)
    {
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $query = DB::table('pembelian')
        ->select('vendor_id', DB::raw('COUNT(*) as total'))
        ->groupBy('vendor_id');

    if ($startDate) {
        $query->whereDate('purchase_date', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('purchase_date', '<=', $endDate);
    }

    return response()->json($query->get());
    }   


    public function selisihbeli(): JsonResponse 
    {
        $results = DB::table('pembelian')
            ->select('category', DB::raw('AVG(DATEDIFF(expected_delivery_date, purchase_date)) as average_days'))
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return response()->json($results);
    }
    
    public function selisih2()  
    {
        $selisih2 = "
        SELECT category,
        ROUND(AVG(DATEDIFF(expected_delivery_date, purchase_date))) AS expected 
        from pembelian
        GROUP BY category
        ORDER BY expected DESC
        ";

        $selisihbaru = DB::select($selisih2);
        
        return response()->json($selisihbaru);
        
    }

   public function ProfitByProduct(Request $request)
    {
    $start = $request->query('start_date');
    $end = $request->query('end_date');

    $sql = "
        SELECT 
            item_name,
            SUM(quantity) AS total_sold,
            SUM(grand_total - (buy_price * quantity)) AS total_profit
        FROM pembelian
        WHERE 1 = 1
    ";

    $bindings = [];

    if ($start) {
        $sql .= " AND DATE(purchase_date) >= ?";
        $bindings[] = $start;
    }

    if ($end) {
        $sql .= " AND DATE(purchase_date) <= ?";
        $bindings[] = $end;
    }

    $sql .= " GROUP BY item_name ORDER BY total_profit DESC";

    $result = DB::select($sql, $bindings);

    return response()->json($result);
    }

    
    public function ProductProfitDetail($item_name, Request $request)
    {
    $start = $request->query('start_date');
    $end = $request->query('end_date');

    $sql = "
        SELECT 
            item_code,
            quantity,
            buy_price,
            unit_price,
            (unit_price - buy_price) * quantity AS total_profit
        FROM pembelian
        WHERE item_name = ?
    ";

    $bindings = [$item_name];

    if ($start) {
        $sql .= " AND DATE(purchase_date) >= ?";
        $bindings[] = $start;
    }

    if ($end) {
        $sql .= " AND DATE(purchase_date) <= ?";
        $bindings[] = $end;
    }

    $result = DB::select($sql, $bindings);

    return response()->json($result);
    }

    public function ProfitByCategory($category, Request $request)
    {
        $item_name = $request->query('item_name');

        $sql = "
            SELECT
                purchase_order_number,
                item_name,
                quantity,
                category,
                (unit_price - buy_price) * quantity AS total_profit
            FROM pembelian
            WHERE category = ?
        ";

        $bindings = [$category];

        if ($item_name) {
            $sql .= " AND item_name = ?";
            $bindings[] = $item_name;
        }

        $data = DB::select($sql, $bindings);

        return response()->json($data);
    }


    public function getCategories()
    {
        $categories = DB::select("SELECT DISTINCT category FROM pembelian ORDER BY category");

        // Extract just the 'category' values into a simple array
        $categories = array_map(fn($row) => $row->category, $categories);

        // Return as JSON response
        return response()->json($categories);


    }

    public function getitemsname($category)
    {

        $sql = "
            SELECT DISTINCT item_name
            FROM pembelian
            WHERE category = ?
            ORDER by item_name
                  
        ";

        $items = DB::select($sql, [$category]);

        $itemname = array_map(fn($row) => $row->item_name, $items);

        return response()->json($itemname);

    }

}