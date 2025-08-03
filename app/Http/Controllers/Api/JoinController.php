<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
class JoinController extends Controller
{
    //Query
    public function getAllVendors()
    {
    $vendors = DB::table('vendors')->select('id', 'name')->orderBy('name')->get();
    return response()->json($vendors);
    }

    public function detailAll($vendor_id, Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $perPage = (int) $request->query('per_page', 10);
        $page = (int) $request->query('page', 1);
        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT 
                pembelian.vendor_id,
                vendors.name AS vendor_name,
                pembelian.purchase_order_number,
                pembelian.item_name,
                pembelian.item_code,
                pembelian.category,
                pembelian.quantity
            FROM pembelian
            JOIN vendors ON pembelian.vendor_id = vendors.id
            WHERE 1 = 1
        ";

        $bindings = [];

        if ($vendor_id != 0) {
            $sql .= " AND pembelian.vendor_id = ?";
            $bindings[] = $vendor_id;
        }

        if ($start) {
            $sql .= " AND DATE(pembelian.purchase_date) >= ?";
            $bindings[] = $start;
        }

        if ($end) {
            $sql .= " AND DATE(pembelian.purchase_date) <= ?";
            $bindings[] = $end;
        }

        // Count total rows (without LIMIT)
        $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as count_table";
        $totalResult = DB::selectOne($countSql, $bindings);
        $total = $totalResult->total;

        // Add pagination
        $sql .= " LIMIT ? OFFSET ?";
        $bindings[] = $perPage;
        $bindings[] = $offset;

        $results = DB::select($sql, $bindings);

        return response()->json([
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
        ]);
    }



    //filtering system

    public function getPembelianColumns()
    {
    $columns = Schema::getColumnListing('pembelian');
    return response()->json($columns);
    }

    public function searchPembelian(Request $request)
    {
        $column = $request->query('column');
        $value = $request->query('value');

        // Define valid columns from the table
        $validColumns = [
            'vendor_id', 'project_id', 'requested_by', 'purchase_order_number',
            'item_name', 'item_code', 'category', 'quantity', 'unit',
            'buy_price', 'unit_price', 'total_price', 'tax', 'grand_total',
            'purchase_date', 'expected_delivery_date', 'status', 'remarks'
        ];

        // If no column or value is given, return all data
        if (!$column || !$value) {
            $sql = "SELECT * FROM pembelian LIMIT 100"; // limit to prevent overload
            $results = DB::select($sql);
            return response()->json($results);
        }

        // Validate column
        if (!in_array($column, $validColumns)) {
            return response()->json(['error' => 'Invalid column'], 400);
        }

        // Use exact match for numeric columns, LIKE for text
        $numericColumns = ['vendor_id', 'project_id', 'quantity', 'buy_price', 'unit_price', 'total_price', 'tax', 'grand_total'];
        if (in_array($column, $numericColumns)) {
            $sql = "SELECT * FROM pembelian WHERE $column = ?";
            $bindings = [$value];
        } else {
            $sql = "SELECT * FROM pembelian WHERE $column LIKE ?";
            $bindings = ["%$value%"];
        }

        $results = DB::select($sql, $bindings);
        return response()->json($results);
    }


    public function getVendorColumns()
    {
        $columns = DB::getSchemaBuilder()->getColumnListing('vendors');
        return response()->json($columns);
    }

    public function searchVendors(Request $request)
    {
        $column = $request->query('column');
        $value = $request->query('value');

        $query = DB::table('vendors');

        if ($column && $value) {
            if (in_array($column, DB::getSchemaBuilder()->getColumnListing('vendors'))) {
                $query->where($column, 'like', "%$value%");
            } else {
                return response()->json(['error' => 'Invalid column'], 400);
            }
        }

        $results = $query->get();
        return response()->json($results);
    }


    public function getRequestColumns()
    {
        $columns = DB::getSchemaBuilder()->getColumnListing('request');
        return response()->json($columns);
    }

    // Search request data
    public function searchRequest(Request $request)
    {
    $column = $request->query('column');
    $value = $request->query('value');

    $query = DB::table('request');

    if ($column && $value) {
        $validColumns = DB::getSchemaBuilder()->getColumnListing('request');

        if (!in_array($column, $validColumns)) {
            return response()->json(['error' => 'Invalid column'], 400);
        }

        // Use exact match for status (can add more exact match columns if needed)
        if (in_array($column, ['status', 'department'])) {
            $query->where($column, $value);
        } else {
            $query->where($column, 'like', "%$value%");
        }
    }

    $results = $query->get();
    return response()->json($results);
    }

    public function getProjectColumns()
    {
        $columns = DB::getSchemaBuilder()->getColumnListing('projects');
        return response()->json($columns);
    }

    public function searchProjects(Request $request)
    {
        $column = $request->query('column');
        $value = $request->query('value');

        $query = DB::table('projects');

        if ($column && $value) {
            if (in_array($column, DB::getSchemaBuilder()->getColumnListing('projects'))) {
                // Use exact match for numeric ID, partial match for text
                if ($column === 'id') {
                    $query->where($column, $value);
                } else {
                    $query->where($column, 'like', "%$value%");
                }
            } else {
                return response()->json(['error' => 'Invalid column'], 400);
            }
        }

        $results = $query->get();
        return response()->json($results);
    }
    
    public function insert(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return response()->json(['message' => 'Project inserted successfully!', 'data' => $project]);
    }

}
