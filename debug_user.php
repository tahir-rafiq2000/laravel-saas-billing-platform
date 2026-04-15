<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'user@example.com')->first();
if ($user) {
    echo "ID: " . $user->id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "StripeID: " . ($user->stripe_id ?: 'NULL') . "\n";
    echo "Local Subscription Count: " . $user->subscriptions()->count() . "\n";
} else {
    echo "User not found.\n";
}
