<x-app-layout>
    <x-slot name="header">
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
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:bg-gray-800">
                    <table style="border-collapse: collapse;width: 100%;" class="dark:bg-gray-800 dark:text-gray-200">

                        <tr style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD;" class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">Item
                                Name</th>
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">Item
                                Number
                            </th>
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">
                                Quantity</th>
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">Clinic</th>
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">Status
                            </th>
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">
                                Requester</th>
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">
                                Requested At:
                            </th>
                            <!--email response to request note request are not bulked
                            <th style="padding: 8px;text-align: center;border-bottom: 1px solid #DDD; font-size: 18px">
                                View
                            </th>
                        -->
                        </tr>
                        <tr>
                            @foreach ($requests as $request)
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD;  border-left: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->item_name}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->item_number}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->item_quantity}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->clinic}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->status}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->requester}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">{{$request->created_at}}</th>
                            <th style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                <form action="{{ route('viewrequest') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $request->id }}">
                                    <button type="submit" class="btn" style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <i class="fas fa-check" style="color: blue; font-size: 24px;" title="Approve"></i>
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