<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Subscription Successful') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-green-100 text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900 mb-4">You're Subscribed!</h3>
            <p class="text-gray-600 mb-8 text-lg">
                Thank you for your purchase. Your subscription is now active and you have full access to all features.
            </p>
            <a href="{{ route('dashboard') }}" class="inline-block w-full bg-indigo-600 text-white font-bold py-4 px-6 rounded-xl hover:bg-indigo-700 transition duration-300 shadow-lg shadow-indigo-100">
                Go to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
