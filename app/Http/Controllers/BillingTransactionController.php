<?php

namespace App\Http\Controllers;

use App\Models\BillingTransaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $billingTransactions = BillingTransaction::with('customer')->get();
        if ($request->has('periode')) {
            $billingTransactions = $billingTransactions->where('periode', $request->periode);
        }else{
            $billingTransactions = $billingTransactions->where('periode', date('Ym'));
        }
        
        return view('billing-transactions.index', compact('billingTransactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('billing-transactions.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'periode' => 'required|string|max:255',
            'bandwith' => 'nullable|string|max:255',
            'pemakaian' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'harga_satuan' => 'nullable|numeric',
        ]);

        BillingTransaction::create($validated);

        return redirect()->route('billing-transactions.index')
            ->with('success', 'Billing transaction created successfully.');
    }

    public function show(BillingTransaction $billingTransaction)
    {
        $billingTransaction->load('customer');
        return view('billing-transactions.show', compact('billingTransaction'));
    }

    public function edit(BillingTransaction $billingTransaction)
    {
        $customers = Customer::all();
        return view('billing-transactions.edit', compact('billingTransaction', 'customers'));
    }

    public function update(Request $request, BillingTransaction $billingTransaction)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'periode' => 'required|string|max:255',
            'bandwith' => 'nullable|string|max:255',
            'pemakaian' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'harga_satuan' => 'nullable|numeric',
        ]);

        $billingTransaction->update($validated);

        return redirect()->route('billing-transactions.index')
            ->with('success', 'Billing transaction updated successfully.');
    }

    public function destroy(BillingTransaction $billingTransaction)
    {
        $billingTransaction->delete();

        return redirect()->route('billing-transactions.index')
            ->with('success', 'Billing transaction deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        $file = $request->file('excel_file');
        
        if (!$file) {
            return redirect()->route('billing-transactions.index')
                ->with('error', 'No file was uploaded. Please select a file.');
        }
        
        if (!$file->isValid()) {
            return redirect()->route('billing-transactions.index')
                ->with('error', 'Invalid file upload. Error: ' . $file->getError());
        }

        $extension = $file->getClientOriginalExtension();
        
        // Debug: Log file information
        Log::info('File upload info', [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $extension,
            'real_path' => $file->getRealPath(),
            'pathname' => $file->getPathname(),
        ]);

        try {
            $data = [];
            
            if ($extension === 'csv') {
                $data = $this->readCsvFile($file);
            } else {
                $data = $this->readExcelFile($file, $extension);
            }

            if (empty($data)) {
                return redirect()->route('billing-transactions.index')
                    ->with('error', 'No data found in the file.');
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::beginTransaction();
            
            foreach ($data as $index => $row) {                
                try {                    
                    // Check if id_pelanggan and periode are filled
                    $idPelanggan = trim($row['id_pelanggan'] ?? '');
                    $periode = trim($row['periode'] ?? '');
                    $bandwith = trim($row['bandwith'] ?? '');
                    
                    if (empty($idPelanggan)) {
                        $errors[] = "Row " . ($index + 2) . ": id_pelanggan is empty. Skipped.";
                        $errorCount++;
                        continue;
                    }

                    if (empty($periode)) {
                        $errors[] = "Row " . ($index + 2) . ": periode is empty. Skipped.";
                        $errorCount++;
                        continue;
                    }

                    if (empty($bandwith)) {
                        $errors[] = "Row " . ($index + 2) . ": bandwith is empty. Skipped.";
                        $errorCount++;
                        continue;
                    }

                    // Find customer by id_pelanggan
                    $customer = Customer::where('id_pelanggan', $idPelanggan)->first();

                    if (!$customer) {
                        $errors[] = "Row " . ($index + 2) . ": Customer with id_pelanggan '{$idPelanggan}' not found.";
                        $errorCount++;
                        continue;
                    }

                    // Update or create billing transaction based on customer_id and periode
                    BillingTransaction::updateOrCreate(
                        [
                            'customer_id' => $customer->id,
                            'bandwith' => $bandwith,                            
                        ],
                        [
                            'periode' => $periode,
                            'pemakaian' => isset($row['pemakaian']) && $row['pemakaian'] !== '' ? (float)$row['pemakaian'] : null,
                            'total' => isset($row['total']) && $row['total'] !== '' ? (float)$row['total'] : null,
                            'harga_satuan' => isset($row['harga_satuan']) && $row['harga_satuan'] !== '' ? (float)$row['harga_satuan'] : null,
                        ]
                    );

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    $errorCount++;
                    Log::error('Import error on row ' . ($index + 2) . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            $message = "Import completed. Success: {$successCount}, Errors: {$errorCount}";
            
            if ($errorCount > 0 && !empty($errors)) {
                return redirect()->route('billing-transactions.index')
                    ->with('error', $message . '. ' . implode(' ', array_slice($errors, 0, 5)))
                    ->withErrors(['import_errors' => $errors]);
            }

            return redirect()->route('billing-transactions.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error: ' . $e->getMessage());
            return redirect()->route('billing-transactions.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    private function readCsvFile($file)
    {
        $data = [];
        // Get the real path of the uploaded file (temporary file path)
        $filePath = $file->getRealPath();
        
        if (!$filePath || !file_exists($filePath)) {
            // Fallback: try getPathname() if getRealPath() doesn't work
            $filePath = $file->getPathname();
            if (!$filePath || !file_exists($filePath)) {
                throw new \Exception('CSV file path is invalid or file does not exist. Path: ' . ($filePath ?? 'null'));
            }
        }
        
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            throw new \Exception('Could not open CSV file for reading.');
        }
        
        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [];
        }

        // Normalize headers (remove spaces, convert to lowercase)
        $headers = array_map(function($header) {
            return trim(strtolower(str_replace(' ', '_', $header)));
        }, $headers);

        // Check required columns
        $requiredColumns = ['id_pelanggan', 'periode', 'bandwith', 'pemakaian', 'total', 'harga_satuan'];
        foreach ($requiredColumns as $required) {
            if (!in_array($required, $headers)) {
                throw new \Exception("Required column '{$required}' not found in CSV file.");
            }
        }

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($headers)) {
                continue; // Skip malformed rows
            }
            
            $data[] = array_combine($headers, $row);
        }

        fclose($handle);
        return $data;
    }

    private function readExcelFile($file, $extension)
    {
        // Check if PhpSpreadsheet is available
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            throw new \Exception('PhpSpreadsheet library is required for Excel files. Please install it: composer require phpoffice/phpspreadsheet');
        }

        // Get the real path of the uploaded file (temporary file path)
        $filePath = $file->getRealPath();
        
        if (!$filePath || !file_exists($filePath)) {
            // Fallback: try getPathname() if getRealPath() doesn't work
            $filePath = $file->getPathname();
            if (!$filePath || !file_exists($filePath)) {
                throw new \Exception('Excel file path is invalid or file does not exist. Path: ' . ($filePath ?? 'null'));
            }
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (empty($rows) || count($rows) < 2) {
            return [];
        }

        // Get headers from first row
        $headers = array_map(function($header) {
            return trim(strtolower(str_replace(' ', '_', $header ?? '')));
        }, $rows[0]);

        // Check required columns
        $requiredColumns = ['id_pelanggan', 'periode', 'bandwith', 'pemakaian', 'total', 'harga_satuan'];
        foreach ($requiredColumns as $required) {
            if (!in_array($required, $headers)) {
                throw new \Exception("Required column '{$required}' not found in Excel file.");
            }
        }

        // Get column indices
        $columnIndices = [];
        foreach ($requiredColumns as $col) {
            $columnIndices[$col] = array_search($col, $headers);
        }

        // Read data rows
        $data = [];
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $rowData = [];
            foreach ($columnIndices as $key => $index) {
                $rowData[$key] = isset($row[$index]) ? trim($row[$index]) : null;
            }
            
            $data[] = $rowData;
        }

        return $data;
    }
}
