<x-app-layout>
    <x-slot name="header" class="bg-gray-400 dark:bg-green-950">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2 class="font-semibold text-xl text-black dark:text-gray-700 leading-tight mt-2">
                        {{ __('Main Stock Transactions') }}
                    </h2>
                </div>
                <div class="col-sm">
                    <div class="py-1" style="float:right;">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>{{ \Session::get('success') }}</p>
                            </div>
                        @endif

                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                            Search Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 dark:bg-gray-800">
                    <center>
                        <!-- Table Structure -->
                        <div id="printArea" class="print-area">
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table
                                    class="table table-striped table-bordered w-full dark:bg-gray-800 dark:text-gray-200">
                                    <!-- Table Header -->
                                    <thead>
                                        <tr class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">Clinic</th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">Procurer</th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">Received By
                                            </th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">Completed At
                                            </th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">Received At
                                            </th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">P_O_D</th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">P_O_R</th>
                                            <th style="padding: 8px; text-align: center; font-size: 18px;">Details</th>

                                        </tr>
                                    </thead>
                                    <!-- Table Body -->
                                    <tbody>
                                        @foreach ($entries as $entry)
                                            <tr class="dark:text-gray-200">
                                                <td>{{ $entry->clinics }}</td>
                                                <td>{{ $entry->procurer }}</td>
                                                <td>{{ $entry->recieved_by }}</td>
                                                <td>{{ $entry->created_at }}</td>
                                                <td>{{ $entry->updated_at }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#viewModal{{ $entry->id }}">
                                                        View
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#viewrModal{{ $entry->id }}">
                                                        View
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#detailsModal{{ $entry->id }}">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Proof of Delivery Modal -->
                                            <div class="modal fade" id="viewModal{{ $entry->id }}" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content dark:bg-gray-800">
                                                        <div class="modal-header bg-green-600 text-white">
                                                            <h5 class="modal-title">Proof of Delivery - ID
                                                                {{ $entry->id }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            @if ($entry->p_o_d)
                                                                <iframe src="{{ asset($entry->p_o_d) }}"
                                                                    style="width: 100%; height: 500px;" frameborder="0">
                                                                    Your browser does not support iframes.
                                                                </iframe>
                                                            @else
                                                                <p>No file available for preview.</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{ asset($entry->p_o_d) }}"
                                                                class="btn btn-success" download>Download PDF</a>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Proof of Receipt Modal -->
                                            <div class="modal fade" id="viewrModal{{ $entry->id }}" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content dark:bg-gray-800">
                                                        <div class="modal-header bg-green-600 text-white">
                                                            <h5 class="modal-title">Proof of Receipt - ID
                                                                {{ $entry->id }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            @if ($entry->p_o_r)
                                                                <iframe src="{{ asset($entry->p_o_r) }}"
                                                                    style="width: 100%; height: 500px;" frameborder="0">
                                                                    Your browser does not support iframes.
                                                                </iframe>
                                                            @else
                                                                <p>No file available for preview.</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{ asset($entry->p_o_r) }}"
                                                                class="btn btn-success" download>Download PDF</a>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Transaction Details Modal -->
                                            <div class="modal fade" id="detailsModal{{ $entry->id }}" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content dark:bg-gray-800">
                                                        <div class="modal-header bg-green-600 text-white">
                                                            <h5 class="modal-title">Transaction Details - ID
                                                                {{ $entry->id }}</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php

                                                                $details = json_decode($entry->details, true); // Decode JSON data
                                                            @endphp
                                                            @if (is_array($details))
                                                                <div class="table-responsive">
                                                                    <div class="container mt-4">
                                                                        <div class="row">
                                                                            @foreach ($details as $detail)
                                                                                <div class="col-md-4 mb-4">
                                                                                    <div class="card h-100">
                                                                                        <div
                                                                                            class="card-body d-flex flex-column">
                                                                                            <h5 class="card-title">
                                                                                                {{ $detail['item_name'] }}
                                                                                            </h5>
                                                                                            <div class="flex-grow-1">
                                                                                                <p><strong>Item
                                                                                                        Number:</strong>
                                                                                                    {{ $detail['item_number'] }}
                                                                                                </p>
                                                                                                <p><strong>Quantity:</strong>
                                                                                                    {{ $detail['item_quantity'] }}
                                                                                                </p>
                                                                                                <p><strong>Price:</strong>
                                                                                                    ${{ DB::table('stock_items')->where('item_number', $detail['item_number'])->value('price') * $detail['item_quantity'] }}

                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                nothing here
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Modal --}}
    <div class="modal fade mt-11" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header" style="background-color: green; color: white;">
                    <h5 class="modal-title" id="searchModalLabel">Search Transactions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/StockTransactions/search">
                    <div class="modal-body">
                        @csrf
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="item_name" class="dark:text-white">Item Name</label>
                                    <input type="text" id="item_name" name="item_name" placeholder="Item Name"
                                        class="form-control" style="border-radius: 9px">
                                </div>
                                <div class="col">
                                    <label for="item_number" class="dark:text-white">Item Number</label>
                                    <input type="text" id="item_number" name="item_number"
                                        placeholder="Item Number" class="form-control" style="border-radius: 9px">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="clinics" class="dark:text-white">Choose a Clinic</label>
                                    <select name="clinics" id="clinics" class="form-control"
                                        style="border-radius: 9px">
                                        <option value="" disabled selected>Select a clinic</option>
                                        @foreach (DB::table('clinics')->get() as $clinic)
                                            <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="procurer" class="dark:text-white">Procurer</label>
                                    <input type="text" id="procurer" name="procurer" placeholder="Procurer"
                                        class="form-control" style="border-radius: 9px">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="transaction_date_from" class="dark:text-white">Transaction
                                        Date</label>
                                    <div class="d-flex justify-content-between">
                                        <input type="date" id="transaction_date_from" name="transaction_date_from"
                                            class="form-control" style="border-radius: 9px">
                                        <span class="mx-2 align-self-center">-</span>
                                        <input type="date" id="transaction_date_to" name="transaction_date_to"
                                            class="form-control" style="border-radius: 9px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Back to Top Button -->
    <button id="backToTopBtn" class="btn btn-primary" onclick="scrollToTop()"
        style="position: fixed; bottom: 20px; right: 20px; display: none; z-index: 1000;">
        <i class="fa fa-arrow-up"></i> Back to Top
    </button>

    <script>
        // Function to scroll the page back to the top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show the button when the user scrolls down 100px
        window.onscroll = function() {
            var btn = document.getElementById("backToTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };
    </script>
</x-app-layout>
