<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\mainstock_journal;
use App\Models\pending_stock;
use App\Models\pending_stocks;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;

class mainStockController extends Controller
{
    public function showmain()
    {
        $stock = StockItem::all();

        return view(
            'mainStock.mainStock',
            [
                'stock' => $stock
            ]
        );
    }

    public function addnewitem(Request $request)
    {
        $request->validate(
            [
                'item_name' => 'required',
                'item_quantity' => 'required',
            ]
        );
        DB::beginTransaction();

        try {
            $rawName = $request->item_name;
            $itemNumber = strtoupper(substr(str_replace(' ', '_', $rawName), 0, 4)) . rand(100, 999); // Add 3 random digits
            $items['item_name'] = $request->item_name;
            $items['item_number'] = $itemNumber;
            $items['item_quantity'] = $request->item_quantity;
            StockItem::create($items);
            $clinics = Clinic::all();
            foreach ($clinics as $clinic) {
                $clinicname = $clinic->clinic_name;
                $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinicname); // Clean clinic name
                $tableName = strtolower($tableName) . '_stocks';
                DB::table($tableName)->insert($items);
            }

            DB::commit();

            return redirect()->route('mainstock')->with('success', 'Product Added.');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Log the error message for debugging
            Log::error('Error deleting stock item: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while deleting the stock.');
        }
    }
    public function searchmain(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        $pending = DB::table('stock_items')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('mainstock')->with('error', 'Product could not be found');
        } else {
            return view('Mainstock.search', ['search' => $pending]);
        }
    }

    public function updatemain(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $oldItemNumber = $request->oldItemNumber;

            // Find the stock item by ID
            $stock = StockItem::findOrFail($id);
            //generate new item number
            $rawName = $validated['item_name'];
            do {
                $itemNumber = strtoupper(substr(str_replace(' ', '_', $rawName), 0, 4)) . rand(100, 999); // Add 3 random digits
            } while (StockItem::where('item_number', $itemNumber)->exists()); // Ensure it's unique
            $stock->item_name = $validated['item_name'];
            $stock->item_number = $itemNumber;
            $stock->price = $validated['price'] * 1.4; // Mark up price by 40%
            $stock->save();
            $clinics = Clinic::get();

            foreach ($clinics as $clinic) {
                $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic->clinic_name); // Clean clinic name
                $tableName = strtolower($tableName) . '_stocks';  // Use clinic name as the table name

                // CURRENT stock in clinic 
                if (Schema::hasTable($tableName)) {
                    DB::table($tableName)
                        ->where('item_number', $oldItemNumber)
                        ->update(['item_number' => $itemNumber]);
                } else {
                    Log::warning("Table {$tableName} does not exist.");
                    continue;
                }
            }
            DB::commit();

            // Redirect with a success message
            return redirect()->back()->with('success', 'Stock updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Log the error message for debugging
            Log::error('Error deleting stock item: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while updating the stock.');
        }
    }

    // Redirect with a success message

    public function deletemain($id)
    {
        // Start a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Find the stock item by ID
            $stock = StockItem::findOrFail($id);
            $itemNumber = $stock->item_number;

            // Delete the stock item from the StockItem table
            $stock->delete();

            // Delete from all related clinic stock tables
            $clinics = Clinic::get();
            foreach ($clinics as $clinic) {
                $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic->clinic_name); // Clean clinic name
                $tableName = strtolower($tableName) . '_stocks'; // Use clinic name as the table name

                // Check if the clinic's stock table exists
                if (Schema::hasTable($tableName)) {
                    // Delete the stock item from the clinic's stock table
                    DB::table($tableName)
                        ->where('item_number', $itemNumber)
                        ->delete();
                }
            }

            // Commit the transaction if all deletions succeed
            DB::commit();

            // Redirect with a success message
            return redirect()->back()->with('success', 'Stock deleted successfully from all clinics.');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Log the error message for debugging
            Log::error('Error deleting stock item: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while deleting the stock.');
        }
    }


    public function searchitem(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        $pending = DB::table('stock_items')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('mainstock')->with('error', 'Product could not be found');
        } else {
            return view('Mainstock.search', ['search' => $pending]);
        }
    }

    public function bulkform()
    {
        return view('Mainstock.bulkform');
    }

    public function bulkformadd()
    {
        return view('Mainstock.bulkformadd');
    }



    public function bulksend(Request $request)
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

        mainstock_journal::create([
            'total_items' => $totalDrugs,
            'procurer' => $procurer,
            'clinics' => $clinics,
            'p_o_d' => 'uploads/pdfs/' . $fileName,
            'details' => json_encode($bulkDetails), // Store bulk details as JSON

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

    public function bulkadd(Request $request)
    {
        $request->validate([
            'drug_name' => 'required|array',
            'drug_name.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'uom' => 'required|array',
            'uom.*' => 'required|string',
            'price' => 'nullable|array',
            'price.*' => 'nullable|numeric|min:0',
        ]);
        $procurer = auth()->user()->name;
        $bulkDetails = [];
        $totalDrugs = count($request->drug_name);
        foreach ($request->drug_name as $index => $drugName) {
            $drugQuantity = $request->quantity[$index] * $request->uom[$index];
            $stockItem = StockItem::where('item_number', $drugName)->first();
            $newStock = $stockItem->item_quantity + $drugQuantity;
            $item['item_quantity'] = $newStock;
            $price = $request->price[$index];
            $item['price'] = ($price * 0.4) + $price;
            $items['user'] = auth()->user()->name;
            $stockItem->update($item);

            // Collect details for the bulk journal and pending stock entries
            $bulkDetails[] = [
                'item_name' => $stockItem->item_name,
                'item_quantity' => $drugQuantity,
                'item_number' => $stockItem->item_number,
                'price' => $request->price,
            ];
        }
        mainstock_journal::create([
            'total_items' => $totalDrugs,
            'procurer' => $procurer,
            'details' => json_encode($bulkDetails), // Store bulk details as JSON

        ]);

        return redirect()->back()->with('success', 'Bulk Addition completed successfully!');
    }
}
