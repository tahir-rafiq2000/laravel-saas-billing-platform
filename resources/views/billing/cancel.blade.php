<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Subscription Cancelled') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-orange-100 text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-orange-100 mb-6">
                <svg class="h-10 w-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900 mb-4">Payment Cancelled</h3>
            <p class="text-gray-600 mb-8 text-lg">
                Your payment process was cancelled and no charges were made. If you had trouble with the checkout, please try again.
            </p>
            <a href="{{ route('billing') }}" class="inline-block w-full bg-indigo-600 text-white font-bold py-4 px-6 rounded-xl hover:bg-indigo-700 transition duration-300">
                Back to Pricing
            </a>
        </div>
    </div>
</x-app-layout>
