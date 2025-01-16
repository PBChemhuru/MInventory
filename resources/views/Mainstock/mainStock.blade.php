<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                        Add New Stock Item
                    </button>
                </div>
                <div class="col-sm">
                    <div style="display:inline">

                        <a href="{{ route('bulkform') }}"><button type="button" class="btn btn-success">
                                <i class="fas fa-shipping-fast"></i>
                                Distribute Stock
                            </button></a>
                        <a href="{{ route('bulkformadd') }}"><button type="button" class="btn btn-primary">
                                <i class="fas fa-warehouse"></i>
                                Add Stock
                            </button></a>
                    </div>
                </div>
                <div class="col-sm">
                    <form action="{{ route('searchmainstock') }}" method="GET">
                        <input type="text" name="isearch" id="isearch" value="{{ old('isearch') }}">
                        <button type="submit">Search</button>
                    </form>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table style="border-collapse: collapse;width: 100%;">
                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item Name</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item Number</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Quantity</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Price</th>

                        </tr>


                        @foreach ($stock as $stocks)
                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->item_name }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->item_number }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->item_quantity }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->price }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#EditStockModal{{ $stocks->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#DeleteStockModal{{ $stocks->id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            {{-- Edit Stock Modal --}}
                            <div class="modal fade" id="EditStockModal{{ $stocks->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="EditStockModal{{ $stocks->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding: 0px;height:50px">
                                            <div style="color:white;width:100%;height:100%;background-color:green;text-align:center"
                                                class="modal-title" id="EditStockModal{{ $stocks->id }}">
                                                <p style="padding-top:10px;display:inline">Edit Stock</p>
                                                <button style="display:inline" type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <form method="POST" action="/mainstock/{{ $stocks->id }}"
                                            enctype="multipart/form-data">
                                            <div style="padding: 10px;">
                                                @csrf
                                                @method('patch')
                                                <div>
                                                    <label for="item_name">Item Name</label><br>
                                                    <input type="text" id="item_name" name="item_name"
                                                        placeholder="Item Name" style="width: 100%;"
                                                        value="{{ $stocks->item_name }}">
                                                    @error('item_name')
                                                        <p style="color:red;size:13px">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="price">Price</label><br>
                                                    <input type="number" id="price" name="price"
                                                        placeholder="1000" style="width: 100%;"
                                                        value="{{ $stocks->price }}">
                                                    @error('price')
                                                        <p style="color:red;size:13px">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <input type="text" id="oldItemNumber" name="oldItemNumber"
                                                    placeholder="1000" style="width: 100%;"
                                                    value="{{ $stocks->item_number }}" hidden>

                                                <p style="margin: 4px">Note: The price you input will be marked up 40%
                                                    before being saved.</p>
                                                <input type="submit"
                                                    style="background-color: green;color:white;padding:5px;margin:15px;border-radius:5px;border-style:outset;border-color:black"
                                                    value="SAVE CHANGES">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Stock Modal --}}
                            <div class="modal fade" id="DeleteStockModal{{ $stocks->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="DeleteStockModal{{ $stocks->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding: 0px;height:50px">
                                            <div style="color:white;width:100%;height:100%;background-color:red;text-align:center"
                                                class="modal-title" id="DeleteStockModal{{ $stocks->id }}">
                                                <p style="padding-top:10px;display:inline">Delete Stock</p>
                                                <button style="display:inline" type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('mainstock.delete', $stocks->id) }}">
                                            @csrf
                                            @method('delete')
                                            <div style="padding: 10px;">
                                                <p>Are you sure you want to delete this item?</p>
                                                <strong>Item: {{ $stocks->item_name }}</strong><br>
                                                <P>NB:Delete this here will delete across all clinic</P>
                                                <input type="submit"
                                                    style="background-color: red;color:white;padding:5px;margin:15px;border-radius:5px;border-style:outset;border-color:black"
                                                    value="DELETE ITEM">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </table>

                    {{-- add newStock model design start here --}}
                    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="padding: 0px;height:50px">
                                    <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                        class="modal-title" id="addModal">
                                        <p style="padding-top:10px;display:inline">ADD TO STOCK</p>
                                        <button style="display:inline" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <form method="POST" action="/mainstock" enctype="multipart/form-data">
                                    <div style="padding-left:10px;padding-right:10px;width:100%">
                                        @csrf
                                        <div>
                                            <label for='item_name'>Item Name</label><br>
                                            <input type="text" id="item_name" name="item_name"
                                                style="width: 100%;"><br>
                                            @error('item_name')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for='quantity'>Quantity </label><br>
                                            <input type="number" id="item_quantity" name="item_quantity"
                                                placeholder="1000" style="width: 100%;"><br>
                                            @error('item_quantity')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </div>




                                        <input type="submit"
                                            style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                            value="ADD TO STOCK">
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
