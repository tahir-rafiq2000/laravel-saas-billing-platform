<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Subscription Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 {{ $isSubscribed ? 'border-green-500' : 'border-gray-300' }}">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 rounded-full {{ $isSubscribed ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Subscription Status</h3>
                                <p class="text-sm text-gray-500">
                                    @if($isSubscribed)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active Subscriber
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Not Subscribed
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('billing') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $isSubscribed ? __('Manage Plans') : __('Choose a Plan') }}
                            </a>
                            
                            @if($isSubscribed)
                                <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Customer Portal') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Current Subscriptions (Live from Stripe) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Your Current Subscriptions</h3>
                        <span class="px-2 py-1 text-xs font-bold bg-indigo-100 text-indigo-700 rounded uppercase tracking-widest flex items-center">
                            <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full mr-1.5 animate-pulse"></span>
                            Live from Stripe
                        </span>
                    </div>

                    @if(count($stripeSubscriptions) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan / Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ending / Renewal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($stripeSubscriptions as $sub)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">{{$sub->items->data[0]->price->nickname ?? 'Standard Subscription'}}</div>
                                                <div class="text-xs text-gray-500">{{$sub->items->data[0]->price->id}}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'active' => 'bg-green-100 text-green-800 border-green-200',
                                                        'canceled' => 'bg-red-100 text-red-800 border-red-200',
                                                        'past_due' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    ];
                                                    $color = $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $color }}">
                                                    {{ ucfirst($sub->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ date('M d, Y', $sub->current_period_end) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ date('M d, Y', $sub->created) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No active subscriptions detected</h3>
                            <p class="mt-1 text-sm text-gray-500">If you have subscriptions on a different account, please check your .env keys.</p>
                            <div class="mt-6">
                                <a href="{{ route('billing') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Browse Plans
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
                </div>
            </div>

                        <!-- Invoice History Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Invoice History</h3>
                    
                    @if(count($invoices) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoices as $invoice)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $invoice->date()->toFormattedDateString() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ $invoice->total() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Paid
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-bold">View →</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-gray-500 italic">No recent invoices found. Detailed history is available in your <a href="{{ route('billing.portal') }}" class="text-indigo-600 font-bold hover:underline">Billing Portal</a>.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        </div>
    </div>
</x-app-layout>
