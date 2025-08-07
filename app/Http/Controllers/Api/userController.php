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


        public function getReportData(Request $request)
        {
            $vendorId = $request->input('vendor_id', null);
            $projectId = $request->input('project_id', null);
            $category = $request->input('category', null);
            $requestedBy = $request->input('requested_by', null);
            $month = $request->input('month', null);

            $query = DB::table('pembelian as p')
                ->select(
                    'p.id',
                    'p.vendor_id',
                    'p.project_id',
                    'p.requested_by',
                    'p.purchase_order_number',
                    'p.item_name',
                    'p.item_code',
                    'p.category',
                    'p.quantity',
                    'p.unit',
                    'p.buy_price',
                    'p.unit_price',
                    'p.total_price',
                    'p.tax',
                    'p.grand_total',
                    'p.purchase_date',
                    'p.expected_delivery_date',
                    'v.name as vendor_name',
                    'pr.name as project_name',
                    'r.name as request_name'
                )
                ->leftJoin('vendors as v', 'p.vendor_id', '=', 'v.id')
                ->leftJoin('projects as pr', 'p.project_id', '=', 'pr.id')
                ->leftJoin('request as r', 'p.requested_by', '=', 'r.id');

                if ($vendorId) {
                    $query->where('p.vendor_id', $vendorId);
                }
                if ($projectId) {
                    $query->where('p.project_id', $projectId);
                }
                if ($category) {
                    $query->where('p.category', $category);
                }
                if ($requestedBy) {
                    $query->where('p.requested_by', $requestedBy);
                }
                if ($month) {
                    $query->whereMonth('p.purchase_date', $month);
                }

                $data = $query->orderBy('p.purchase_date', 'desc')->get();

                return response()->json($data);
        }

        public function reporting()
        {
            $vendors = DB::table('vendors')->select('id', 'name')->get();
            $projects = DB::table('projects')->select('id', 'name')->get();
            $requests = DB::table('request')->select('id', 'name')->get();
            $categories = DB::table('pembelian')->distinct()->pluck('category');

            return view('Master.report', compact('vendors', 'projects', 'requests', 'categories'));
        }

    }