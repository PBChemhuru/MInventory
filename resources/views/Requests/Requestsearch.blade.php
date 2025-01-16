<x-app-layout>
    <x-slot name="header" class="">
        @if (auth()->user()->Role == 'Admin' or auth()->user()->role == 'Accountant')
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
                <x-nav-link :href="route('showrequests')" :active="request()->routeIs('showrequests')">
                    {{ __('Pending Requests') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
                <x-nav-link :href="route('showarequests')" :active="request()->routeIs('showarequests')">
                    {{ __('Approved Requests') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
                <x-nav-link :href="route('showdrequests')" :active="request()->routeIs('showdrequests')">
                    {{ __('Denied Requests') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
                <x-nav-link :href="route('showallrequests')" :active="request()->routeIs('showallrequests')">
                    {{ __('Search Requests') }}
                </x-nav-link>
            </div>

            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex " style="float:right">
                @if (count($errors) > 0)
                    <div class="alert alert-danger ">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>
                    </div>
                @endif
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
                    Search Requests
                </button>
            </div>
        @else
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex " style="float:right">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>
                    </div>
                @endif
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

                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal">
                    Search Requests
                </button>
            </div>
        @endif


    </x-slot>


    {{-- modal design search --}}
    <div class="modal fade" id="searchrModal" tabindex="-1" role="dialog" aria-labelledby="searchrModalLabel"
        aria-hidden="true">
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
                                <input type="text" id="item_name" name="item_name" placeholder="Enter Item Name"
                                    class="form-control">
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
                                    <?php
                                    $clinics = DB::table('clinics')->get('clinic_name');
                                    ?>

                                    <option value="" disabled selected>Select a clinic</option>
                                    @foreach ($clinics as $clinic)
                                        <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}</option>
                                    @endforeach
                                </select>
                                @error('clinics')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" id="status" name="status" placeholder="Enter Status"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="requester" class="form-label">Requester</label>
                                <input type="text" id="requester" name="requester" placeholder="Enter Requester Name"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="approver" class="form-label">handler</label>
                                <input type="text" id="approver" name="approver" placeholder="Enter handler Name"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="transaction_date_from" class="form-label">Transaction Date From</label>
                                <input type="date" id="transaction_date_from" name="transaction_date_from"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="transaction_date_to" class="form-label">Transaction Date To</label>
                                <input type="date" id="transaction_date_to" name="transaction_date_to"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="width: 70%;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Button to print results -->
                <button class="btn btn-primary mb-3" onclick="printResults()">
                    <i class="fa fa-print"></i> Print Results
                </button>
                <button class="btn btn-success mb-3"
                    onclick="location.href='{{ route('arequests.export.csv') }}'">
                    <i class="fa fa-download"></i> Download CSV
                </button>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="printArea" class="print-area">

                        <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Item Name</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Item Number
                                    </th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Quantity</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Clinic</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Status</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Requester</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Requested At
                                    </th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Handled By
                                    </th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Handled At
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $request)
                                    <tr>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->item_name }}
                                        </td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->item_number }}
                                        </td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                            {{ $request->item_quantity }}</td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->clinic }}</td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->status }}</td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->requester }}
                                        </td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->created_at }}
                                        </td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $request->approver }}
                                        </td>
                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                            {{ $request->date_approved }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
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
