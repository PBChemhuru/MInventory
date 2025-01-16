<x-app-layout>
    <x-slot name="header">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('pendingstock')" :active="request()->routeIs('pendingstock')">
                {{ __('Pending Stock') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('receivedstock')" :active="request()->routeIs('receivedstock')">
                {{ __('Received Stock') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline;float:right">
            <div class="col-sm">

                <div class="col-sm">
                    <div class="py-1" style="float:right;">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                            Search Records
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </x-slot>

    <center>
        <div style="width:80%;margin-top:30px">
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
        </div>
    </center>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Recived stocks') }}

                    <center>
                        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                            <!-- Table Header -->
                            <thead style="background-color: #f4f4f4; color: #333;">
                                <tr>
                                    <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Clinic</th>
                                    <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Status</th>
                                    <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Procurer</th>
                                    <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Sent At</th>
                                    <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Receiver</th>
                                    <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Proof of delivery</th>
                                        <th
                                        style="padding: 12px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Transaction Details</th>
                                </tr>
                            </thead>

                            <!-- Table Body -->
                            <tbody>
                                @foreach ($rstocks as $rstock)
                                    <tr style="background-color: #fff; border-bottom: 1px solid #ddd;">
                                        <td style="padding: 10px; text-align: left; border: 1px solid #ddd;">
                                            {{ $rstock->clinics }}</td>
                                        <td style="padding: 10px; text-align: left; border: 1px solid #ddd;">
                                            {{ $rstock->status }}</td>
                                        <td style="padding: 10px; text-align: left; border: 1px solid #ddd;">
                                            {{ $rstock->procurer }}</td>
                                        <td style="padding: 10px; text-align: left; border: 1px solid #ddd;">
                                            {{ $rstock->created_at }}</td>
                                        <td style="padding: 10px; text-align: left; border: 1px solid #ddd;">
                                            {{ $rstock->reciever }}</td>
                                        <td style="padding: 10px; text-align: left; border: 1px solid #ddd;"><button
                                                type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#receiveModal{{ $rstock->id }}"><i
                                                    class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#detailsModal{{ $rstock->id }}">
                                                View
                                            </button>
                                        </td>
                                        {{-- receive stock model design start here --}}
                                        <div class="modal fade" id="receiveModal{{ $rstock->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="receiveModal{{ $rstock->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0px;height:50px">
                                                        <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                                            class="modal-title" id="receiveModal{{ $rstock->id }}">
                                                            <h5 class="modal-title"
                                                                id="viewModalLabel{{ $rstock->id }}">
                                                                Proof of Delivery {{ $rstock->id }}</h5>
                                                            <button style="display:inline" type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <iframe src="{{ asset($rstock->p_o_r) }}" style="width: 100%; height: 500px;" frameborder="0">
                                                            Your browser does not support iframes.
                                                        </iframe>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ asset($rstock->p_o_r) }}" class="btn btn-success" download>Download PDF</a>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                    


                                                </div>
                                            </div>
                                        </div>
                                        <!-- Transaction Details Modal -->
                                        <div class="modal fade" id="detailsModal{{ $rstock->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content dark:bg-gray-800">
                                                    <div class="modal-header bg-green-600 text-white">
                                                        <h5 class="modal-title">Transaction Details - ID
                                                            {{ $rstock->id }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $details = json_decode($rstock->details, true); // Decode JSON data
                                                        @endphp
                                                        <div class="table-responsive">
                                                            <div class="container mt-4">
                                                                <div class="row">
                                                                    @foreach ($details as $detail)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div class="card h-100">
                                                                                <div
                                                                                    class="card-body d-flex flex-column">
                                                                                    <h5 class="card-title">
                                                                                        {{ $detail['item_name'] }}</h5>
                                                                                    <div class="flex-grow-1">
                                                                                        <p><strong>Item Number:</strong>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </center>

                </div>


            </div>
        </div>
    </div>

    {{-- modal design search --}}
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: green; color: white;">
                    <h5 class="modal-title" id="searchModalLabel">SEARCH</h5>
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
                                    <input type="text" id="item_name" name="item_name" placeholder="Item Name"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="item_number">Item Number</label>
                                    <input type="text" id="item_number" name="item_number"
                                        placeholder="Item Number" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="procurer">Procurer</label>
                                    <input type="text" id="procurer" name="procurer" placeholder="Procurer"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="transaction_date_from">Transaction Date</label>
                                    <div class="d-flex justify-content-between">
                                        <input type="date" id="transaction_date_from" name="transaction_date_from"
                                            class="form-control">
                                        <span class="mx-2 align-self-center">-</span>
                                        <input type="date" id="transaction_date_to" name="transaction_date_to"
                                            class="form-control">
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

</x-app-layout>
