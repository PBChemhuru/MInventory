<?php

namespace App\Http\Controllers;

use App\Models\mainstock_journal;
use App\Models\pending_stocks;
use App\Models\stock_request;
use App\Models\StockItem;
use App\Models\Transferrecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class clincStockController extends Controller
{
    public function showpending()
    {

        $pending = DB::table('pending_stocks')->where('clinics', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Pending')->get();

        return view('clinicstock.pendingstock', ['pstocks' => $pending]);
    }

    public function receivedstock()
    {
        $Received = DB::table('pending_stocks')->where('clinics', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Received')->get();

        return view('clinicstock.receivedstock', ['rstocks' => $Received]);
    }
    public function searchpstock(Request $request)
    {

        $searchTerm = $request->input('ssearch'); // Get the search query from the request
        $pending = [];
        $pending =  DB::table('pending_stocks')->where('clinics', 'LIKE', Auth::user()->clinic)->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('pendingstock')->with('error', 'Product could not be found');
        } else {
            return view('clinicstock.pendingstocksearch', ['search' => $pending]);
        }
    }


    public function changestatus(Request $request)
    {

        $request->validate([
            'item_pdf' => 'required|mimes:pdf|max:2048',
        ]);
        if ($request->hasFile('item_pdf')) {
            $file = $request->file('item_pdf');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Create a unique file name
            $file->move(public_path('uploads/pdfs/'), $fileName);
        }
        $id = $request->id;
        $update['status'] = 'Received';
        $update['reciever'] = Auth::user()->name;
        $update['p_o_r'] = 'uploads/pdfs/' . $fileName;
        $approved = pending_stocks::where('id', 'like', $id)->get()->first();

        mainstock_journal::where('item_number', 'like', $approved->item_number)
            ->where('clinics', 'like', $approved->clinics)
            ->where('created_at', 'like', $approved->created_at)
            ->where('item_quantity', 'like', $approved->item_quantity)
            ->where('item_number', 'like', $approved->item_number)
            ->update([
                'recieved_by' => auth()->user()->name,
                'p_o_r' => 'uploads/pdfs/' . $fileName,
                // Add any other fields as necessary
            ]);;
        $approve = pending_stocks::find($id);
        $match = Transferrecord::where('transdetail', $approve->details)
            ->where('sender', $approve->procurer)
            ->where('created_at', $approve->created_at)
            ->first();
        if ($match) {
            $match->receiver = Auth::user()->name;
            $match->status = 'Received';
            $match->save();
        }
        $approve->update($update);
        $addstock = $approve->item_quantity;
        $clinic = $approved->clinics;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $details = json_decode($approve->details);
        foreach ($details as $detail) {
            $currenstock = DB::table($tableName)->where('item_number', 'like', $detail->item_number)->get()->first()->item_quantity;
            $addstocks = $detail->item_quantity;
            $newstock = $addstocks + $currenstock;
            DB::table($tableName)
                ->where('item_number', 'like',  $detail->item_number)
                ->update(['item_quantity' => $newstock]);
            $stockItem = StockItem::where('item_number', $detail->item_number)->first();
            $newcentralStock = $stockItem->item_quantity - $detail->item_quantity;
            $stockItem->update(['item_quantity' => $newcentralStock]);
        }
        return redirect()->route('pendingstock')->with('success', 'Stock Received.');
    }


    public function searchclinicstock(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $pending = DB::table($tableName)->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

        if ($pending->isEmpty()) {
            return redirect()->route('getclinicstock')->with('error', 'Product could not be found');
        } else {

            return view('clinicstock.clinicstocksearch', ['clinicstock' => $pending]);
        }
    }
    public function getclinicstock()
    {
        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $clinicstock = DB::table($tableName)->get();
        return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
    }

    public function requeststock()
    {
        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        return view('clinicstock.requeststock', ['drugs' => $drugs]);
    }

    public function searchrstock(Request $request)
    {

        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        $request->validate([
            'item_name' => 'nullable|string|max:255',
            'item_number' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'requester' => 'nullable|string|max:255',
            'transaction_date_from' => 'nullable|date',
            'transaction_date_to' => 'nullable|date',
        ]);

        // Start the query
        $query = pending_stocks::query();

        $query->where('clinics', auth()->user()->clinic);
        $query->where('status', 'like', 'Received');


        // Apply filters based on input
        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        if ($request->filled('item_number')) {
            $query->where('item_number', 'like', '%' . $request->item_number . '%');
        }

        if ($request->filled('requester')) {
            $query->where('requester', 'like', '%' . $request->requester . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        if ($request->filled('transaction_date_from') && $request->filled('transaction_date_to')) {
            $query->whereRaw('DATE(updated_at) BETWEEN ? AND ?', [
                $request->transaction_date_from,
                $request->transaction_date_to
            ]);
        } elseif ($request->filled('transaction_date_from')) {
            $query->whereRaw('DATE(updated_at) >= ?', [$request->transaction_date_from]);
        } elseif ($request->filled('transaction_date_to')) {
            $query->whereRaw('DATE(updated_at) <= ?', [$request->transaction_date_to]);
        }


        $results = $query->get();
        session(['search_results' => $results]);

        return view('clinicstock.receivedstocksearch', compact('results', 'drugs'));
    }

    public function saverequest(Request $request)
    {
        $things['item_name'] = $request->item_name;
        $things['item_quantity'] = $request->item_quantity;
        $things['item_number'] = $request->item_number;
        $things['clinic'] = auth()->user()->clinic;
        $things['requester'] = auth()->user()->name;
        $things['status'] = "Pending";
        $things['date_requested'] = Carbon::now()->toDatetimeString();

        stock_request::create($things);
        $things['approver'] =
            $things['date_approved'] =



            $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        return redirect()->route('requeststock', ['drugs' => $drugs])->with('success', 'Stock Requested.');
    }

    public function stocktransfer()
    {
        $records = Transferrecord::where('clinic_to', auth()->user()->clinic)
            ->orwhere('clinic_from', auth()->user()->clinic)->get();

        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();

        return view('clinicstock.transfers', compact('records', 'drugs'));
    }

    public function gettransferpage()
    {
        return view('clinicstock.transferdrug');
    }

    public function bulktransfer(Request $request)
    {

        $request->validate([
            'clinics' => 'required',
            'drug_name' => 'required|array',
            'drug_name.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'item_pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $clinics = $request->clinics;
        $procurer = auth()->user()->name;


        $bulkDetails = [];
        $totalDrugs = count($request->drug_name);

        foreach ($request->drug_name as $index => $drugName) {
            $drugQuantity = $request->quantity[$index];
            $stockItem = StockItem::where('item_number', $drugName)->first();
            if ($drugQuantity > $stockItem->item_quantity) {
                // Handle insufficient stock
                return redirect()->back()->with('error', "Not enough stock for '{$drugName}'. Current stock: {$stockItem->item_quantity}.");
            }

            // Collect details for the bulk journal and pending stock entries
            $bulkDetails[] = [
                'item_name' => $stockItem->item_name,
                'item_quantity' => $drugQuantity,
                'item_number' => $drugName
            ];
        }
        if ($request->hasFile('item_pdf')) {
            $file = $request->file('item_pdf');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Create a unique file name
            $file->move(public_path('uploads/pdfs/'), $fileName);
        }

        Transferrecord::create([
            'total_items' => $totalDrugs,
            'sender' => $procurer,
            'clinic_to' => $clinics,
            'p_o_d' => 'uploads/pdfs/' . $fileName,
            'transdetail' => json_encode($bulkDetails), // Store bulk details as JSON
            'clinic_from' => auth()->user()->clinic,
            'status' => 'Pending',

        ]);

        // Save pending stock entry
        pending_stocks::create([
            'status' => 'Pending',
            'procurer' => $procurer,
            'clinics' => $clinics,
            'total_items' => $totalDrugs,
            'details' => json_encode($bulkDetails), // Store bulk details as JSON
        ]);

        return redirect()->back()->with('success', 'Bulk distribution completed successfully!');
    }

    public function savetransfer(Request $request)

    {
        $transfer['drug_name'] = $request->drug_name;
        $transfer['clinic_from'] = auth()->user()->clinic;
        $transfer['drug_amount'] = $request->drug_amount;
        $transfer['clinic_to'] = $request->clinic_to;
        $transfer['sender'] = auth()->user()->name;
        $transfer['status'] = 'Pending';
        Transferrecord::create($transfer);
        $pending['item_name'] = $request->drug_name;
        $pending['item_quantity'] = $request->drug_amount;
        $pending['item_number'] = $request->item_number;
        $pending['procurer'] = auth()->user()->name;
        $pending['status'] = 'Pending';
        $pending['clinics'] = $request->clinic_to;
        pending_stocks::create($pending);
        $removestock = $request->drug_amount;

        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $currenstock = DB::table($tableName)->where('item_number', 'like', $request->item_number)->get()->first()->item_quantity;
        $newstock = $currenstock - $removestock;
        DB::table($tableName)
            ->where('item_number', 'like', $request->item_number)
            ->update(['item_quantity' => $newstock]);

        redirect()->route('stocktransfer')->with('success', 'Transfer sent');
    }



    public function searchtransfer(Request $request)
    {

        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        $query = Transferrecord::query();  // Start with the base query for DrugTransfer

        // Apply filters based on the input parameters
        if ($request->filled('drug_name')) {
            $query->where('drug_name', 'like', '%' . $request->drug_name . '%');
        }
        if ($request->filled('clinic_from')) {
            $query->where('clinic_from', 'like', '%' . $request->clinic_from . '%');
        }
        if ($request->filled('sender')) {
            $query->where('sender', 'like', '%' . $request->sender . '%');
        }
        if ($request->filled('drug_amount')) {
            $query->where('drug_amount', '=', $request->drug_amount);
        }
        if ($request->filled('clinic_to')) {
            $query->where('clinic_to', 'like', '%' . $request->clinic_to . '%');
        }
        if ($request->filled('receiver')) {
            $query->where('receiver', 'like', '%' . $request->receiver . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }
        if ($request->filled('send_at_start') && $request->filled('send_at_end')) {
            $query->whereBetween(DB::raw('DATE(updated_at)'), [$request->send_at_start, $request->send_at_end]);
        } elseif ($request->filled('send_at_start')) {
            $query->where(DB::raw('DATE(updated_at)'), '>=', $request->send_at_start);
        } elseif ($request->filled('send_at_end')) {
            $query->where(DB::raw('DATE(updated_at)'), '<=', $request->send_at_end);
        }

        if ($request->filled('received_at_start') && $request->filled('received_at_end')) {
            $query->whereBetween(DB::raw('DATE(updated_at)'), [$request->received_at_start, $request->received_at_end]);
        } elseif ($request->filled('received_at_start')) {
            $query->where(DB::raw('DATE(updated_at)'), '>=', $request->received_at_start);
        } elseif ($request->filled('received_at_end')) {
            $query->where(DB::raw('DATE(updated_at)'), '<=', $request->received_at_end);
        }
        // Get the filtered records
        $results = $query->get();
        session(['search_results' => $results]);
        return view('clinicstock.transfersearch', compact('results', 'drugs'));
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
                'Clinic',
                'Status',
                'Received By',
                'Requested At',
                'Procurer',
                'Received ',
                'P.O.D',
                'Transaction Details',

            ]);

            // Write each result to the CSV
            foreach ($results as $result) {
                fputcsv($file, [
                    $result->clinics,           // Clinic
                    $result->status,           // Status
                    $result->reciever,      // Received By
                    $result->created_at,     // Requested At
                    $result->procurer,
                    $result->updated_at,
                    $result->p_o_d,            // P.O.D (Proof of Delivery)
                    json_decode($result->transaction_details), // Transaction Details in JSON format
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportrCsv()
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
                'Item_name',
                'Item_number',
                'Quantity',
                'Clinic',
                'Status',
                'Requester',
                'Requested At',
                'Handler',
                'Handled At',

            ]);

            // Write each result to the CSV
            foreach ($results as $result) {
                fputcsv($file, [
                    $result->item_name,
                    $result->item_number,
                    $result->item_quantity,
                    $result->clinic,             // Clinic
                    $result->status,           // Status
                    $result->requester,      // Received By
                    $result->date_requested,     // Requested At
                    $result->approver,
                    $result->date_approved,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
