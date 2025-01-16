<?php

namespace App\Http\Controllers;

use App\Models\dispense;
use App\Models\employees;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DispenseController extends Controller
{
    public function dispenseform(Request $request)
    {


        $drug = $request->item_name;
        $drug_number = $request->item_number;


        return view('clinicstock.dispense', compact('drug', 'drug_number'));
    }

    public function dispense(Request $request)
    {

        $request->validate([
            'damount' => "required",

        ]);
        $dispense['drug_number'] = $request->drug_number;
        $dispense['drug'] = $request->drug;
        $dispense['damount'] = $request->damount;
        $dispense['dispenser'] = auth()->user()->name;
        $dispense['dispense_time'] = Carbon::now()->toDatetimeString();
        $dispense['clinic'] = auth()->user()->clinic;
        $damount = $request->damount;
        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $currenstock = DB::table($tableName)->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
        if ($damount < $currenstock) {
            $newstock = $currenstock - $damount;
            DB::table($tableName)
                ->where('item_number', 'like', $request->drug_number)
                ->update(['item_quantity' => $newstock]);
            dispense::create($dispense);
            return redirect()->route('getclinicstock')->with('success', 'Stock Dispensed.');
        }

        return redirect()->route('requeststock')->with('error', 'Request more stock');
    }

    public function dispensehistory()
    {
        $disclinic = auth()->user()->clinic;
        $clinichis = dispense::where('clinic', $disclinic)->get();



        return view('clinicstock.dispensedstock', compact('clinichis'));
    }

    public function searchhis(Request $request)
    {

        $query = dispense::query();

        // Apply filters based on the request inputs

        // UIN
        if ($request->filled('UIN')) {
            $query->where('UIN', 'like', '%' . $request->UIN . '%');
        }

        // drug
        if ($request->filled('drug')) {
            $query->where('drug', 'like', '%' . $request->drug . '%');
        }


        // dispenser
        if ($request->filled('dispenser')) {
            $query->where('dispenser', $request->dispenser);
        }

        // Transaction Date
        if ($request->filled('transaction_date_from') && $request->filled('transaction_date_to')) {
            $query->whereBetween(DB::raw('DATE(dispense_time)'), [$request->transaction_date_from, $request->transaction_date_to]);
        } elseif ($request->filled('transaction_date_from')) {
            $query->where(DB::raw('DATE(dispense_time)'), '>=', $request->transaction_date_from);
        } elseif ($request->filled('transaction_date_to')) {
            $query->where(DB::raw('DATE(dispense_time)'), '<=', $request->transaction_date_to);
        }

        // Execute the query and get the results
        $results = $query->get();
        session(['search_results' => $results]);

        return view('clinicstock.dispensesearch', ['results' => $results]);
    }

    public function dispensehistoryadmin()
    {
        $clinichis = dispense::all();

        return view('admin.dispensedstock', compact('clinichis'));
    }

    public function searchhisadmin(Request $request)
    {

        $query = dispense::query();

        // Apply filters based on the request inputs

        // UIN
        if ($request->filled('UIN')) {
            $query->where('UIN', 'like', '%' . $request->UIN . '%');
        }

        // drug
        if ($request->filled('drug')) {
            $query->where('drug', 'like', '%' . $request->drug . '%');
        }


        // dispenser
        if ($request->filled('dispenser')) {
            $query->where('dispenser', $request->dispenser);
        }

        if ($request->filled('clinic')) {
            $query->where('clinic', $request->clinic);
        }
        // Transaction Date
        if ($request->filled('transaction_date_from') && $request->filled('transaction_date_to')) {
            $query->whereBetween(DB::raw('DATE(dispense_time)'), [$request->transaction_date_from, $request->transaction_date_to]);
        } elseif ($request->filled('transaction_date_from')) {
            $query->where(DB::raw('DATE(dispense_time)'), '>=', $request->transaction_date_from);
        } elseif ($request->filled('transaction_date_to')) {
            $query->where(DB::raw('DATE(dispense_time)'), '<=', $request->transaction_date_to);
        }

        // Execute the query and get the results
        $results = $query->get();
        session(['search_results' => $results]);


        return view('admin.dispensesearch', ['results' => $results]);
    }

    public function exportCsv()
    {
        $results = session('search_results', []);

        if (empty($results)) {
            return back()->with('error', 'No search results found to export.');
        }

        // Generate CSV response
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="search_results.csv"',
        ];

        $callback = function () use ($results) {
            $file = fopen('php://output', 'w');


            // Add the CSV headers
            fputcsv($file, [
                'Drug',
                'Quantity',
                'Dispenser',
                'Clinic',
                'Date/Time',
            ]);
    
            // Add data rows
            foreach ($results as $result) {
                fputcsv($file, [
                    $result->drug,      // Drug Name
                    $result->damount,       // Quantity
                    $result->dispenser,      // Dispenser
                    $result->clinic,         // Clinic
                    $result->dispense_time,     // Date/Time
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
