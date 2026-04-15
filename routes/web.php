<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    $invoices = [];
    $stripeSubscriptions = [];
    $isSubscribed = false;

    if ($user->hasStripeId()) {
        try {
            $stripeCustomer = $user->asStripeCustomer(['subscriptions']);
            $stripeSubscriptions = $stripeCustomer->subscriptions->data;
            $invoices = $user->invoices();
            $isSubscribed = count($stripeSubscriptions) > 0;
        } catch (\Exception $e) {
            // Handle Stripe issues
        }
    }

    return view('dashboard', [
        'isSubscribed' => $isSubscribed,
        'stripeSubscriptions' => $stripeSubscriptions,
        'invoices' => $invoices,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::post('/billing/sync', [BillingController::class, 'sync'])->name('billing.sync');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
