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
            <form action="{{ route('searchpstock') }}" method="GET">
                <input type="text" name="ssearch" id="ssearch" value="{{ old('ssearch') }}">
                <button type="submit">Search</button>
            </form>
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
                    {{ __("Pending stocks") }}
                    @if ($search->isEmpty())
                        <p>No pending request</p>
                    @else
                    <center>
                        <table style="border-collapse: collapse;width: 100%;">

                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item
                                    Name</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item
                                    Number
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Quantity</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Clinic</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Status
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    procurer</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Sent at:
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Action
                                </th>
                            </tr>
                            <tr>
                                @foreach ($search as $pstock)
                                <td>{{$pstock->item_name}}</td>
                                <td>{{$pstock->item_number}}</td>
                                <td>{{$pstock->item_quantity}}</td>
                                <td>{{$pstock->clinic}}</td>
                                <td>{{$pstock->status}}</td>
                                <td>{{$pstock->procurer}}</td>
                                <td>{{$pstock->created_at}}</td>
                                <td>
                                    <form action="{{ route('changestatus') }}" method="POST">
                                        @method('PATCH')
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $pstock->id }}">
                                        <input type="submit" class="btn btn-success " value="Received" style="margin: 6pt">
                                    </form>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#detailsModal{{ $pstock->id }}">
                                        View
                                    </button>
                                </td>
                                <!--details view modal-->
                                <div class="modal fade" id="detailsModal{{ $pstock->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content dark:bg-gray-800">
                                            <div class="modal-header bg-green-600 text-white">
                                                <h5 class="modal-title">Transaction Details - ID {{ $pstock->id }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @php
                                                    $details = json_decode($pstock->details, true); // Decode JSON data
                                                @endphp
                                                <div class="table-responsive">
                                                    <div class="container mt-4">
                                                        <div class="row">
                                                            @foreach ($details as $detail)
                                                                <div class="col-md-4 mb-4">
                                                                    <div class="card h-100">
                                                                        <div class="card-body d-flex flex-column">
                                                                            <h5 class="card-title">{{ $detail['item_name'] }}</h5>
                                                                            <div class="flex-grow-1">
                                                                                <p><strong>Item Number:</strong> {{ $detail['item_number'] }}</p>
                                                                                <p><strong>Quantity:</strong> {{ $detail['item_quantity'] }}</p>
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
                            </tr>
                        </table>
                    </center>
                    @endif       
                </div>

                
            </div>
        
        </div>
    </div>
</x-app-layout>