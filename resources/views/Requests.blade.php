<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                                Requester</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                Requested at:
                            </th>
                        </tr>
                        <tr>
                            @foreach ($requests as $request)
                            <th>{{$request->item_name}}</th>
                            <th>{{$request->item_number}}</th>
                            <th>{{$request->item_quantity}}</th>
                            <th>{{$request->clinic}}</th>
                            <th>{{$request->status}}</th>
                            <th>{{$request->requester}}</th>
                            <th>{{$request->created_at}}</th>
                            <th>
                                <form action="{{ route('viewrequest') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $request->id }}">
                                    <button type="submit" class="btn" style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <i class="fas fa-eye" style="color: blue; font-size: 24px;" title="Approve"></i>
                                    </button>
                                </form> 

                            </th>
                        </tr>



                        @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>