<x-app-layout>
    <x-slot name="header">
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

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal">
                Search
            </button>

        </div>
        {{-- add REQUEST modal button --}}
        <div class="py-1" style="float:right;">
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

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                Request stock
            </button>

        </div>

    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <button class="btn btn-success mb-3"
                        onclick="location.href='{{ route('requeststock.export.csv') }}'">
                        <i class="fa fa-download"></i> Download CSV
                    </button>

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <table style="border-collapse: collapse;width: 100%;">
                                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Item
                                                Name</th>
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Item
                                                Number
                                            </th>
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Quantity</th>
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Clinic</th>
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Status
                                            </th>
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Requester</th>
                                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                Requested at:
                                            </th>
                                        </tr>
                                        <?php $mypendingrequest = [];
                                        $pastrequests = [];
                                        $mypendingrequest = DB::table('stock_requests')
                                            ->where('requester', '=', auth()->user()->name)
                                            ->get();
                                        
                                        ?>
                                        @if ($mypendingrequest->isEmpty())
                                            <tr>
                                                <td colspan="7"
                                                    style="text-align: center; padding: 16px; color: red;">
                                                    No pending requests available.
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($mypendingrequest as $prequest)
                                                <tr>
                                                    <td>{{ $prequest->item_name }}</td>
                                                    <td>{{ $prequest->item_number }}</td>
                                                    <td>{{ $prequest->item_quantity }}</td>
                                                    <td>{{ $prequest->clinic }}</td>
                                                    <td>{{ $prequest->status }}</td>
                                                    <td>{{ $prequest->requester }}</td>
                                                    <td>{{ $prequest->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
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
                                                    <label for="procurer">Procurer</label>
                                                    <input type="text" id="procurer" name="procurer"
                                                        placeholder="Procurer" class="form-control">
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
                                                    <label for="procurer">Procurer</label>
                                                    <input type="text" id="procurer" name="procurer"
                                                        placeholder="Procurer" class="form-control">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
