<x-app-layout>
    <x-slot name="header">



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
                    <div class="m-15" style="float:right;">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal"
                            sty>
                            Search
                        </button>
                    </div>


                    <div class="py-12">

                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
                                        <thead>
                                            <tr style="background-color: #f2f2f2;">
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Drug</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Quantity</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Dispenser</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Clinic</th>
                                                <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                    Date/Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if ($clinichis->isEmpty())
                                                <tr>
                                                    <td colspan="9"
                                                        style="text-align: center; padding: 16px; color: red;">
                                                        No dispenses matching search requests.
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($clinichis as $hisrequest)
                                                    <tr>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $hisrequest->drug }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $hisrequest->damount }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $hisrequest->dispenser }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $hisrequest->clinic }}</td>
                                                        <td style="padding: 12px; border: 1px solid #ddd;">
                                                            {{ $hisrequest->dispense_time }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
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
                                <div class="modal-header"
                                    style="height: 50px; background-color: green; color: white; text-align: center;">
                                    <h5 class="modal-title" id="searchrModalLabel">SEARCH</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('searchhisadmin') }}">
                                    <div class="container p-4">
                                        @csrf
                                        <h5 class="mb-4">Search Requests</h5>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="UIN" class="form-label">UIN</label>
                                                <input type="text" id="UIN" name="UIN"
                                                    placeholder="Enter UIN" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="drug" class="form-label">Drug Name</label>
                                                <input type="text" id="drug" name="drug"
                                                    placeholder="Enter drug" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="dispenser" class="form-label">Dispenser</label>
                                                <input type="text" id="dispenser" name="dispenser"
                                                    placeholder="Enter Dispenser" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <select name="clinic" id="clinic"
                                                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <?php
                                                    $clinics = DB::table('clinics')->get('clinic_name');
                                                    ?>

                                                    <option value="" disabled selected>Select a clinic</option>
                                                    @foreach ($clinics as $clinic)
                                                        <option value="{{ $clinic->clinic_name }}">
                                                            {{ $clinic->clinic_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
