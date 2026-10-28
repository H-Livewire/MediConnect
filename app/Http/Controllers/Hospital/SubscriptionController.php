<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('hospital.subscription', [
            'hospital' => $request->user()->managedHospital,
            'price' => config('mediconnect.subscription_price'),
            'currency' => config('mediconnect.currency'),
            'period' => config('mediconnect.subscription_days'),
        ]);
    }
}
