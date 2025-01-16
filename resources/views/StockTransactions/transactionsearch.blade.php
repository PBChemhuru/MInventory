<x-app-layout>
    <x-slot name="header">
        <div class="container">
            {{-- add modal button --}}
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
                    Transaction Search
                </button>
            </div>
        </div>
        {{-- modal design --}}
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
                    <form method="POST" action="/StockTransactions/search">
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
                                        <label for="clinics">Choose a Clinic</label>
                                        <select name="clinics" id="clinics" class="form-control">
                                            <?php
                                            $clinics = DB::table('clinics')->get('clinic_name');
                                            ?>
                                            <?php
                                            $clinics = DB::table('clinics')->get('clinic_name');
                                            ?>

                                            <option value="" disabled selected>Select a clinic</option>
                                            @foreach ($clinics as $clinic)
                                                <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('clinics')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                            <input type="date" id="transaction_date_from"
                                                name="transaction_date_from" class="form-control">
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

    </x-slot>

    <div class="py-6"> <!-- Reduced outer padding -->
        <div class="max-w-full mx-auto sm:px-4 lg:px-6"> <!-- Reduced padding for the container -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100"> <!-- Reduced inner padding -->
                    <div class="py-6">
                        <!-- Button to print results -->
                        <button class="btn btn-primary mb-3" onclick="printResults()">
                            <i class="fa fa-print"></i> Print Results
                        </button>
                        <button class="btn btn-success mb-3" onclick="location.href='{{ route('transactions.export.csv') }}'">
                            <i class="fa fa-download"></i> Download CSV
                        </button>



                        <div class=" text-gray-900 dark:text-gray-100">
                            <center>
                                @if ($results->isEmpty())
                                    <p>No results found.</p>
                                @else
                                    <div id="printArea" class="print-area">
                                        <div class="table-responsive" style="overflow-x: auto;">
                                            <table class="table table-striped table-bordered w-full">
                                                <thead>
                                                    <tr>

                                                        <th>Clinic</th>
                                                        <th>Date of Transaction</th>
                                                        <th>Date of Received</th>
                                                        <th>Procurer</th>
                                                        <th>Received By</th>
                                                        <th>P.O.D</th>
                                                        <th>P.O.R</th>
                                                        <th>Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results as $result)
                                                        <tr class="dark:text-gray-200">
                                                            <td>{{ $result->clinics }}</td>
                                                            <td>{{ $result->created_at }}</td>
                                                            <td>{{ $result->updated_at }}</td>                                                              <td>{{ $result->procurer }}</td>
                                                            <td>{{ $result->recieved_by }}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#viewModal{{ $result->id }}">
                                                                    View
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#viewrModal{{ $result->id }}">
                                                                    View
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#detailsModal{{ $result->id }}">
                                                                    View 
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <!-- Proof of Delivery Modal -->
                                                        <div class="modal fade" id="viewModal{{ $result->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content dark:bg-gray-800">
                                                                    <div class="modal-header bg-green-600 text-white">
                                                                        <h5 class="modal-title">Proof of
                                                                            Delivery - ID {{ $result->id }}
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <iframe src="{{ asset($result->p_o_d) }}" style="width: 100%; height: 500px;" frameborder="0">
                                                                            Your browser does not support iframes.
                                                                        </iframe>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ asset($result->p_o_d) }}" class="btn btn-success" download>Download PDF</a>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Proof of Receipt Modal -->
                                                        <div class="modal fade" id="viewrModal{{ $result->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content dark:bg-gray-800">
                                                                    <div class="modal-header bg-green-600 text-white">
                                                                        <h5 class="modal-title">Proof of
                                                                            Receipt - ID {{ $result->id }}
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <iframe src="{{ asset($result->p_o_r) }}" style="width: 100%; height: 500px;" frameborder="0">
                                                                            Your browser does not support iframes.
                                                                        </iframe>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ asset($result->p_o_r) }}" class="btn btn-success" download>Download PDF</a>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Transaction Details Modal -->
                                                        <div class="modal fade" id="detailsModal{{ $result->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content dark:bg-gray-800">
                                                                    <div class="modal-header bg-green-600 text-white">
                                                                        <h5 class="modal-title">Transaction
                                                                            Details - ID {{ $result->id }}
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @php
                                                                            $details = json_decode(
                                                                                $result->details,
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
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                @endif
                            </center>
                        </div>


                    </div>
                </div>
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
