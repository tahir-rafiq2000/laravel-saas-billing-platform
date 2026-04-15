<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Billing & Subscriptions') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
            </div>

            @if (session('error'))
                <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Header Section -->
            <div class="text-center mb-16">
                @if($isSubscribed)
                    <div class="mb-8 inline-flex items-center px-6 py-3 bg-white border border-green-200 rounded-2xl text-green-700 font-semibold shadow-sm">
                        <span class="flex h-3 w-3 mr-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span>Your account is <strong class="text-green-800">Active</strong></span>
                        <div class="mx-4 h-6 w-px bg-gray-200"></div>
                        <a href="{{ route('billing.portal') }}" class="text-indigo-600 hover:text-indigo-800 font-bold transition-all flex items-center">
                            Access Portal Center
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                @endif
                <h3 class="text-4xl font-extrabold text-gray-900 sm:text-5xl tracking-tight">
                    {{ $isSubscribed ? 'Subscription Management' : 'Pricing & Plans' }}
                </h3>
                <p class="mt-4 text-xl text-gray-500 max-w-2xl mx-auto">
                    {{ $isSubscribed 
                        ? 'Manage your current plan, update payment methods, or download invoices through your portal below.' 
                        : 'Simple, transparent pricing. No hidden fees, no surprises. Cancel anytime.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    @php
                        $isPro = $plan->slug === 'pro';
                        $isEnterprise = $plan->slug === 'enterprise';
                    @endphp
                    
                    <div class="{{ $isEnterprise ? 'bg-gray-900 border-gray-800' : 'bg-white border-gray-100' }} rounded-2xl shadow-xl overflow-hidden transform transition duration-500 hover:scale-105 border relative {{ $isPro ? 'border-2 border-indigo-600 shadow-2xl' : '' }}">
                        @if($isPro)
                            <div class="absolute top-0 right-0 bg-indigo-600 text-white px-4 py-1 rounded-bl-xl text-xs font-bold uppercase tracking-widest">
                                Most Popular
                            </div>
                        @endif

                        <div class="p-8">
                            <h4 class="text-2xl font-bold {{ $isEnterprise ? 'text-white' : 'text-gray-900' }}">{{ $plan->name }}</h4>
                            <p class="mt-4 {{ $isEnterprise ? 'text-gray-400' : 'text-gray-500' }} text-sm">{{ $plan->description }}</p>
                            <p class="mt-8">
                                <span class="text-5xl font-extrabold {{ $isEnterprise ? 'text-white' : 'text-gray-900' }}">${{ number_format($plan->price / 100, 0) }}</span>
                                <span class="text-base font-medium {{ $isEnterprise ? 'text-gray-400' : 'text-gray-500' }}">/mo</span>
                            </p>
                                                        @php
                                $isActive = in_array($plan->stripe_id, $activePriceIds);
                            @endphp
                            <form action="{{ route('billing.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plan->slug }}">
                                <button type="submit" 
                                    @if($isActive) disabled @endif
                                    class="mt-8 block w-full {{ $isActive ? 'bg-gray-400 cursor-not-allowed' : ($isEnterprise ? 'bg-white text-gray-900 hover:bg-gray-100' : 'bg-indigo-600 text-white hover:bg-indigo-700') }} border border-transparent rounded-lg py-3 px-6 text-center font-medium transition duration-300 {{ $isPro ? 'shadow-lg shadow-indigo-200' : '' }}">
                                    @if($isActive)
                                        Current Plan
                                    @elseif($isSubscribed)
                                        Upgrade
                                    @else
                                        {{ $isEnterprise ? 'Contact Sales' : 'Get ' . $plan->name }}
                                    @endif
                                </button>
                            </form>
                        </div>

                        <div class="px-8 pb-8">
                            <ul class="space-y-4 {{ $isEnterprise ? 'text-gray-300' : 'text-gray-600' }}">
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="ml-3">Access to all {{ strtolower($plan->name) }} features</span>
                                </li>
                                @if($isPro || $isEnterprise)
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="ml-3">Priority Support</span>
                                    </li>
                                @endif
                                @if($isEnterprise)
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="ml-3">Custom Contracts</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FAQ or Additional info -->
            <div class="mt-16 bg-indigo-50 rounded-3xl p-8 text-center border border-indigo-100 italic text-indigo-700">
                "We switched to this platform and our productivity increased by 40%. The Pro plan is a game changer." - Jane Doe, CEO of TechFlow
            </div>
        </div>
    </div>
</x-app-layout>
