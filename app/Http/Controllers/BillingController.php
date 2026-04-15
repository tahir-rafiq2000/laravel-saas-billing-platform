<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class BillingController extends Controller
{
    /**
     * Display the pricing plans.
     */
        public function index()
    {
        $user = Auth::user();
        $plans = Plan::all();
        
        $activePriceIds = [];
        if ($user->hasStripeId()) {
            try {
                $stripeCustomer = $user->asStripeCustomer(['subscriptions']);
                $activePriceIds = collect($stripeCustomer->subscriptions->data)
                    ->map(fn($sub) => $sub->items->data[0]->price->id)
                    ->toArray();
            } catch (\Exception $e) {
                // Ignore
            }
        }
        
        return view('billing.index', [
            'isSubscribed' => count($activePriceIds) > 0,
            'activePriceIds' => $activePriceIds,
            'plans' => $plans,
        ]);
    }

    /**
     * Redirect to the Stripe Customer Portal.
     */
    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(
            route('dashboard')
        );
    }

    /**
     * Initiate a Stripe Checkout session.
     */
        public function checkout(Request $request)
    {
        $planSlug = $request->input('plan');
        $plan = Plan::where('slug', $planSlug)->first();

        if (!$plan) {
            return redirect()->back()->with('error', 'Invalid plan selected.');
        }

        try {
            $user = Auth::user();

            // If user is already subscribed to a different plan, we handle it as a Swap/Upgrade
            if ($user->subscribed('default')) {
                // To keep it simple and handle Stripe Checkout flow properly,
                // we can either swap immediately OR send them to the Customer Portal.
                // Professional SaaS apps often use the Portal for "Upgrading" mid-term.
                
                // However, for an "Upgrade" button that feels like a checkout:
                return $user->subscription('default')
                    ->checkout([
                        'success_url' => route('billing.success'),
                        'cancel_url' => route('billing.cancel'),
                        'subscription_data' => [
                            'items' => [
                                [
                                    'id' => $user->subscription('default')->items->first()->stripe_id,
                                    'price' => $plan->stripe_id,
                                ],
                            ],
                        ],
                    ]);
            }

            // New Subscription
            return $user
                ->newSubscription('default', $plan->stripe_id)
                ->checkout([
                    'success_url' => route('billing.success'),
                    'cancel_url' => route('billing.cancel'),
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Subscription failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment.
     */
    public function success()
    {
        return redirect()->route('billing.portal')->with('success', 'Subscription activated successfully!');
    }

    /**
     * Handle cancelled payment.
     */
    public function cancel()
    {
        return view('billing.cancel');
    }
}
