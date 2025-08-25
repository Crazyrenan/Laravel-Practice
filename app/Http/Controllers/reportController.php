<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SplFileObject;

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
        return view('Re', compact('vendors'));
    }

    public function getPurchaseReport(Request $request)
    {
        $month = $request->month;
        $project = $request->project;
        $status = $request->status;

        $query = "
            SELECT p.id, p.purchase_order_number, pr.name, p.item_name, p.quantity, p.unit, p.buy_price, p.total_price,
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
    public function downloadPembelianTemplate()
        {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="template_pembelian.csv"',
            ];

            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'vendor_id',          
                    'project_id',          
                    'requested_by',        
                    'purchase_order_number',
                    'item_name',
                    'item_code',
                    'category',
                    'quantity',
                    'unit',
                    'buy_price',
                    'unit_price',
                    'total_price',
                    'tax',
                    'grand_total',
                    'purchase_date',
                    'expected_delivery_date',
                    'status',
                    'remarks'
                ]);

                fclose($file);
            };

            return new StreamedResponse($callback, 200, $headers);
        }
        private function setNullForEmptyStrings(array $data, array $keys): array
        {
            foreach ($keys as $key) {
                if (isset($data[$key]) && trim($data[$key]) === '') {
                    $data[$key] = null;
                }
            }
            return $data;
        }

       public function importPembelian(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $header = fgetcsv($file);

            $errors = [];
            $totalImported = 0;
            $totalUpdated = 0;
            $rowNumber = 1;

            $allVendors = DB::table('vendors')->pluck('id')->all();
            $allProjects = DB::table('projects')->pluck('id')->all();
            $allRequests = DB::table('request')->pluck('id')->all();

            DB::beginTransaction();

            try {
                while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
                    $rowNumber++;
                    $rowData = array_combine($header, $data);
                    $rowData = $this->setNullForEmptyStrings($rowData, [
                        'purchase_order_number', 'item_name', 'item_code', 'category',
                        'unit', 'remarks', 'status'
                    ]);

              
                    $vendorId = $rowData['vendor_id'] ?? null;
                    if (!in_array($vendorId, $allVendors)) {
                        $errors[] = "Row {$rowNumber}: Invalid vendor_id ({$vendorId})";
                    }

       
                    $projectId = $rowData['project_id'] ?? null;
                    if (!in_array($projectId, $allProjects)) {
                        $errors[] = "Row {$rowNumber}: Invalid project_id ({$projectId})";
                    }

        
                    $requestedBy = $rowData['requested_by'] ?? null;
                    if (!in_array($requestedBy, $allRequests)) {
                        $errors[] = "Row {$rowNumber}: Invalid requested_by ({$requestedBy})";
                    }

                    if (!empty($errors)) {
                        break;
                    }

                    $purchaseDate = $rowData['purchase_date'] ? Carbon::parse($rowData['purchase_date'])->format('Y-m-d H:i:s') : null;
                    $expectedDeliveryDate = $rowData['expected_delivery_date'] ? Carbon::parse($rowData['expected_delivery_date'])->format('Y-m-d H:i:s') : null;
                    $currentTime = now();

                    $purchaseData = [
                        'vendor_id' => $vendorId,
                        'project_id' => $projectId,
                        'requested_by' => $requestedBy,
                        'purchase_order_number' => $rowData['purchase_order_number'],
                        'item_name' => $rowData['item_name'],
                        'item_code' => $rowData['item_code'],
                        'category' => $rowData['category'],
                        'quantity' => $rowData['quantity'] ?? 0,
                        'unit' => $rowData['unit'],
                        'buy_price' => $rowData['buy_price'] ?? 0,
                        'unit_price' => $rowData['unit_price'] ?? 0,
                        'total_price' => $rowData['total_price'] ?? 0,
                        'tax' => $rowData['tax'] ?? 0,
                        'grand_total' => $rowData['grand_total'] ?? 0,
                        'purchase_date' => $purchaseDate,
                        'expected_delivery_date' => $expectedDeliveryDate,
                        'status' => $rowData['status'] ?? 'Pending',
                        'remarks' => $rowData['remarks'],
                    ];

                    $existingRecord = DB::table('pembelian')
                        ->where('purchase_order_number', $purchaseData['purchase_order_number'])
                        ->first();

                    if ($existingRecord) {
                        DB::table('pembelian')
                            ->where('purchase_order_number', $purchaseData['purchase_order_number'])
                            ->update(array_merge($purchaseData, ['updated_at' => $currentTime]));
                        $totalUpdated++;
                    } else {
                        DB::table('pembelian')
                            ->insert(array_merge($purchaseData, ['created_at' => $currentTime, 'updated_at' => $currentTime]));
                        $totalImported++;
                    }
                }

                fclose($file);

                if (!empty($errors)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Import failed. ' . implode('; ', $errors));
                }

                DB::commit();
                return redirect()->back()->with('success', "Import completed. Inserted {$totalImported}, updated {$totalUpdated}.");
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
            }
        }
    
        public function updatePembelian(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt',]);
        
        $path = $request->file('file')->getRealPath();
        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);
        $header = $file->current();

        if (!is_array($header) || !in_array('id', $header)) {
            return redirect()->back()->with('error', 'File CSV harus memiliki kolom "id" di baris header.');
        }

        $headerMapping = [
            'id' => 'id',
            'PO Number' => 'purchase_order_number',
            'Project' => 'project_id',
            'Item' => 'item_name',
            'Quantity' => 'quantity',
            'Unit' => 'unit',
            'Buy Price' => 'buy_price',
            'Total Price' => 'total_price',
            'Tax' => 'tax',
            'Grand Total' => 'grand_total',
            'Purchase Date' => 'purchase_date',
            'Expected Delivery' => 'expected_delivery_date',
            'Status' => 'status',
        ];

        $projects = DB::table('projects')->pluck('id', 'name')->all();
        $existingRecordIds = DB::table('pembelian')->pluck('id')->all();

        $results = []; 
        $dataToUpdate = []; 
        $rowNumber = 1;
        $hasFailures = false;

        foreach ($file as $data) {
            $rowNumber++;

            if (!is_array($data) || count(array_filter($data)) === 0) {
                continue;
            }

            if (count($data) !== count($header)) {
                $results[] = [
                    'status' => 'failed',
                    'data' => $data,
                    'errors' => ["Jumlah kolom di baris ini ({$rowNumber}) tidak cocok dengan header."],
                ];
                $hasFailures = true;
                continue;
            }

            $rowData = array_combine($header, $data);
            $recordId = trim($rowData['id']) ?? null;
            $rowErrors = [];

            if (empty($recordId) || !is_numeric($recordId)) {
                $rowErrors[] = "Kolom 'id' kosong atau bukan angka.";
            } else if (!in_array($recordId, $existingRecordIds)) {
                $rowErrors[] = "Record dengan 'id' {$recordId} tidak ditemukan.";
            }

            $projectName = trim($rowData['Project']) ?? null;
            if ($projectName && !isset($projects[$projectName])) {
                $rowErrors[] = "Nama 'Project' '{$projectName}' tidak valid.";
            }

            if (empty($rowErrors)) {
                // Tambahkan data ke array update jika valid
                $rowData['project_id'] = $projects[$projectName];
                $dataToUpdate[] = $rowData;
                $results[] = [
                    'status' => 'success',
                    'data' => $rowData,
                ];
            } else {
                // Tambahkan data ke array hasil dengan status gagal
                $results[] = [
                    'status' => 'failed',
                    'data' => $rowData,
                    'errors' => $rowErrors,
                ];
                $hasFailures = true;
            }
        }
        
        if ($hasFailures) {
        $reportHtml = view('partials.import_report_table', [
            'results' => $results,
            'header' => $header,
            'message' => 'Pembaruan CSV gagal. Harap tinjau kesalahan di bawah ini.'
        ])->render();
        
        session()->flash('import_report_html', $reportHtml);
        return redirect()->back()->with('error', 'Update gagal. Tinjau detail di popup.');
        }
        
        DB::beginTransaction();
        try {
            $totalUpdated = 0;
            foreach ($dataToUpdate as $rowData) {
                $recordId = $rowData['id'];
                $existingRecord = DB::table('pembelian')->where('id', $recordId)->first();
                $updateData = [];
                $oldValues = [];
                $newValues = [];
                $hasChanges = false;
                
                foreach ($headerMapping as $csvHeader => $dbColumn) {
                    if (isset($rowData[$csvHeader])) {
                        $value = trim($rowData[$csvHeader]);
                        if ($dbColumn == 'purchase_date' || $dbColumn == 'expected_delivery_date') {
                            $updateData[$dbColumn] = !empty($value) ? Carbon::parse($value)->format('Y-m-d H:i:s') : $existingRecord->{$dbColumn};
                        } elseif ($dbColumn == 'project_id') {
                            $updateData[$dbColumn] = $projects[$rowData['Project']] ?? $existingRecord->{$dbColumn};
                        } else {
                            $updateData[$dbColumn] = !empty($value) ? $value : $existingRecord->{$dbColumn};
                        }
                    }
                }
                
                $updateData['updated_at'] = now();

                foreach ($updateData as $key => $value) {
                    if (array_key_exists($key, $existingRecord->toArray()) && trim($existingRecord->{$key}) != trim($value)) {
                        $oldValues[$key] = $existingRecord->{$key};
                        $newValues[$key] = $value;
                        $hasChanges = true;
                    }
                }
                
                if ($hasChanges) {
                    DB::table('pembelian')->where('id', $recordId)->update($updateData);
                    DB::table('pembelian_logs')->insert([
                        'pembelian_id' => $recordId,
                        'old_values' => json_encode($oldValues),
                        'new_values' => json_encode($newValues),
                        'updated_by' => 'CSV Update',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $totalUpdated++;
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "Update berhasil. Total {$totalUpdated} baris diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Update gagal: " . $e->getMessage());
        }
    }
}



