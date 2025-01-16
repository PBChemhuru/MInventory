<x-app-layout>
    <x-slot name="header">
        <div class="py-1" style="float:right;padding:10px">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal">
                Search
            </button>
        </div>

        <div class="py-1" style="float:right;">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                Request stock
            </button>
        </div>

        {{-- Consolidated error and success messages --}}
        <div class="py-1" style="clear:both;">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">



                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
                                        <thead>
                                            <tr style="background-color: #f2f2f2;">
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Item Name</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Item Number</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Quantity</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Clinic</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Status</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Requester</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Requested At</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Handled By</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Handled At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $mypendingrequest = DB::table('stock_requests')
                                                ->where('clinic', '=', auth()->user()->clinic)
                                                ->get();
                                            ?>

                                            @if ($mypendingrequest->isEmpty())
                                                <tr>
                                                    <td colspan="9"
                                                        style="text-align: center; padding: 16px; color: red;">
                                                        No pending requests available.
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($mypendingrequest as $prequest)
                                                    <tr>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->item_name }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->item_number }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->item_quantity }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->clinic }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->status }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->requester }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->created_at }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->approver }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $prequest->date_approved }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- request  modal design --}}
                    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog"
                        aria-labelledby="searchModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="padding: 0px;height:50px">
                                    <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                        class="modal-title" id="searchModalLabel">
                                        <p style="padding-top:10px;display:inline">Request</p>
                                        <button style="display:inline" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <form method="POST" action="/requeststock/save">
                                    <div style="padding-left:10px;padding-right:10px;width:100%">
                                        @csrf
                                        <div>
                                            <label for='item_name'>Item Name</label><br>
                                            <select name="item_name" id="item_name" style="width: 100%;"
                                                onchange="autofill()">
                                                <option></option>
                                                @foreach ($drugs as $drug)
                                                    <option value="{{ $drug->item_name }}"
                                                        data-item-number="{{ $drug->item_number }}">
                                                        {{ $drug->item_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="autofillField">Item Number:</label><br>
                                            <input type="text" id="autofillField"
                                                placeholder="Item Number will be autofilled" readonly>
                                            <input type="hidden" name="item_number" id="item_number">
                                            @error('item_name')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for='quantity'>Quantity</label><br>
                                            <input type="number" id="item_quantity" name="item_quantity"
                                                placeholder="1000" style="width: 100%;"><br>
                                            @error('item_quantity')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <input type="submit"
                                            style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                            value="REQUEST STOCK">
                                    </div>
                                </form>

                                <script>
                                    function autofill() {
                                        const dropdown = document.getElementById("item_name");
                                        const selectedOption = dropdown.options[dropdown.selectedIndex];
                                        const itemNumber = selectedOption.getAttribute("data-item-number");
                                        const autofillField = document.getElementById("autofillField");
                                        const hiddenInput = document.getElementById("item_number");
                                        // Set the autofill field based on the selected option's data
                                        autofillField.value = itemNumber || "";
                                        hiddenInput.value = itemNumber || "";
                                    }
                                </script>


                            </div>
                        </div>
                    </div>

                    {{-- modal design search --}}
                    <div class="modal fade" id="searchrModal" tabindex="-1" role="dialog"
                        aria-labelledby="searchrModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"
                                    style="height: 50px; background-color: green; color: white; text-align: center;">
                                    <h5 class="modal-title" id="searchrModalLabel">SEARCH</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('searchrequests') }}">
                                    <div class="container p-4">
                                        @csrf
                                        <h5 class="mb-4">Search Requests</h5>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="item_name" class="form-label">Item Name</label>
                                                <input type="text" id="item_name" name="item_name"
                                                    placeholder="Enter Item Name" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="item_number" class="form-label">Item Number</label>
                                                <input type="text" id="item_number" name="item_number"
                                                    placeholder="Enter Item Number" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="clinics" class="form-label">Choose a Clinic</label>
                                                <select name="clinics" id="clinics" class="form-select">
                                                    <option value="" disabled selected>Select a clinic</option>
                                                    <?php 
                                                    $clinics =DB::table('clinics')->get('clinic_name')?>
                                                    @foreach ($clinics as $clinic)
                                                        <option value="{{ $clinic->clinic_name }}">
                                                            {{ $clinic->clinic_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('clinics')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="status" class="form-label">Status</label>
                                                <input type="text" id="status" name="status"
                                                    placeholder="Enter Status" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="requester" class="form-label">Requester</label>
                                                <input type="text" id="requester" name="requester"
                                                    placeholder="Enter Requester Name" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="approver" class="form-label">Approver</label>
                                                <input type="text" id="approver" name="approver"
                                                    placeholder="Enter Approver Name" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="transaction_date_from" class="form-label">Transaction Date
                                                    From</label>
                                                <input type="date" id="transaction_date_from"
                                                    name="transaction_date_from" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="transaction_date_to" class="form-label">Transaction Date
                                                    To</label>
                                                <input type="date" id="transaction_date_to"
                                                    name="transaction_date_to" class="form-control">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary"
                                                style="width: 70%;">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
