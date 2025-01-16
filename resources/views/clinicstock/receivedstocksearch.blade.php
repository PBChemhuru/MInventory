<x-app-layout>
    <x-slot name="header">
        <div>
            {{-- add SEARCH modal button --}}
            <div class="py-1" style="float:left;">
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
        </div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal">
            Search
        </button>
    </x-slot>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Button to print results -->
                    <button class="btn btn-primary mb-3" onclick="printResults()">
                        <i class="fa fa-print"></i> Print Results
                    </button>
                    <button class="btn btn-success mb-3" onclick="location.href='{{ route('rstock.export.csv') }}'">
                        <i class="fa fa-download"></i> Download CSV
                    </button>
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">

                                    <div id="printArea" class="print-area">
                                        <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
                                            <thead>
                                                <tr style="background-color: #f2f2f2;">
                                                    <th
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        Clinic</th>
                                                    <th
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        Status</th>
                                                    <th
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        Received By</th>
                                                    <th
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        Requested At</th>
                                                    <th
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        P.O.D</th>
                                                    <th
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        Transaction Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($results->isEmpty())
                                                    <tr>
                                                        <td colspan="7"
                                                            style="text-align: center; padding: 16px; color: red;">
                                                            No pending requests available.
                                                        </td>
                                                    </tr>
                                                @else
                                                    @foreach ($results as $prequest)
                                                        <tr>
                                                            <td style="padding: 12px; border: 1px solid #ddd;">
                                                                {{ $prequest->clinics }}</td>
                                                            <td style="padding: 12px; border: 1px solid #ddd;">
                                                                {{ $prequest->status }}</td>
                                                            <td style="padding: 12px; border: 1px solid #ddd;">
                                                                {{ $prequest->reciever }}</td>
                                                            <td style="padding: 12px; border: 1px solid #ddd;">
                                                                {{ $prequest->updated_at }}</td>
                                                            <td style="padding: 12px; border: 1px solid #ddd;">
                                                                <button type="button" class="btn btn-success"
                                                                    data-toggle="modal"
                                                                    data-target="#receiveModal{{ $prequest->id }}"><i
                                                                        class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                            <td style="padding: 12px; border: 1px solid #ddd;">
                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#detailsModal{{ $prequest->id }}">
                                                                    View
                                                                </button>
                                                            </td>

                                                        </tr>
                                                        {{-- receive stock model design start here --}}
                                                        <div class="modal fade" id="receiveModal{{ $prequest->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="receiveModal{{ $prequest->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header"
                                                                        style="padding: 0px;height:50px">
                                                                        <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                                                            class="modal-title"
                                                                            id="receiveModal{{ $prequest->id }}">
                                                                            <h5 class="modal-title"
                                                                                id="viewModalLabel{{ $prequest->id }}">
                                                                                Proof of Delivery
                                                                                {{ $prequest->id }} <a
                                                                                    href="{{ asset('images/' . $prequest->p_o_r) }}"
                                                                                    download="{{ basename($prequest->p_o_r) }}"
                                                                                    class="btn btn-primary">
                                                                                    <i class="fas fa-download"
                                                                                        style="font-size: 30px; color: blue;"></i>
                                                                                </a></h5>
                                                                            <button style="display:inline"
                                                                                type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <iframe src="{{ asset($prequest->p_o_r) }}"
                                                                            style="width: 100%; height: 500px;"
                                                                            frameborder="0">
                                                                            Your browser does not support iframes.
                                                                        </iframe>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ asset($prequest->p_o_r) }}"
                                                                            class="btn btn-success" download>Download
                                                                            PDF</a>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Transaction Details Modal -->
                                                        <div class="modal fade" id="detailsModal{{ $prequest->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content dark:bg-gray-800">
                                                                    <div class="modal-header bg-green-600 text-white">
                                                                        <h5 class="modal-title">Transaction Details - ID
                                                                            {{ $prequest->id }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @php
                                                                            $details = json_decode(
                                                                                $prequest->details,
                                                                                true,
                                                                            ); // Decode JSON data
                                                                        @endphp
                                                                        <div class="table-responsive">
                                                                            <div class="container mt-4">
                                                                                <div class="row">
                                                                                    @foreach ($details as $detail)
                                                                                        <div class="col-md-4 mb-4">
                                                                                            <div class="card h-100">
                                                                                                <div
                                                                                                    class="card-body d-flex flex-column">
                                                                                                    <h5
                                                                                                        class="card-title">
                                                                                                        {{ $detail['item_name'] }}
                                                                                                    </h5>
                                                                                                    <div
                                                                                                        class="flex-grow-1">
                                                                                                        <p><strong>Item
                                                                                                                Number:</strong>
                                                                                                            {{ $detail['item_number'] }}
                                                                                                        </p>
                                                                                                        <p><strong>Quantity:</strong>
                                                                                                            {{ $detail['item_quantity'] }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- modal design --}}
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
                                <div class="modal-header" style="background-color: green; color: white;">
                                    <h5 class="modal-title" id="searchrModalLabel">SEARCH</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('searchrstock') }}">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="container">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="item_name">Item Name</label>
                                                    <input type="text" id="item_name" name="item_name"
                                                        placeholder="Item Name" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="item_number">Item Number</label>
                                                    <input type="text" id="item_number" name="item_number"
                                                        placeholder="Item Number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="status">Status</label>
                                                    <input type="text" id="status" name="status"
                                                        placeholder="Status" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="requester">Requester</label>
                                                    <input type="text" id="requester" name="requester"
                                                        placeholder="Requester" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="transaction_date_from">Transaction Date</label>
                                                    <div class="d-flex justify-content-between">
                                                        <input type="date" id="transaction_date_from"
                                                            name="transaction_date_from" class="form-control">
                                                        <span class="mx-2 align-self-center">-</span>
                                                        <input type="date" id="transaction_date_to"
                                                            name="transaction_date_to" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"
                                            style="width: 100%;">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- print script --}}
    <script>
        function printResults() {
            var printContents = document.getElementById('printArea').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-app-layout>
