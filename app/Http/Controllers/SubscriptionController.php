<?php

namespace App\Http\Controllers;

use App\Enums\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function pricing()
    {
        return view('pricing', [
            'plans' => Plan::cases(),
            'currentPlan' => auth()->check() ? auth()->user()->currentPlan() : null,
        ]);
    }

    public function checkout(Request $request, string $plan)
    {
        $planEnum = Plan::from($plan);

        if (! $planEnum->stripePriceId()) {
            return redirect()->route('pricing');
        }

        return $request->user()
            ->newSubscription('default', $planEnum->stripePriceId())
            ->checkout([
                'success_url' => route('subscribe.success'),
                'cancel_url' => route('pricing'),
            ]);
    }

    public function success()
    {
        return view('subscribe-success');
    }

    public function billingPortal(Request $request)
    {
        $user = $request->user();

        if (! $user->hasStripeId()) {
            return redirect()->route('pricing')
                ->with('status', 'Subscribe to a plan to access the billing portal.');
        }

        return $user->redirectToBillingPortal(route('pricing'));
    }
}
