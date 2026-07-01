<?php

namespace App\Http\Controllers;

use App\Models\ClientsModel;
use App\Models\PackageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
{
    $request->validate([
        'package_id' => 'required|exists:packages,id',
        'amount' => 'required|numeric',
    ]);

    $package = PackageModel::find($request->package_id);
    $client_email = ClientsModel::where('id',$package->client_id)->value('email');
    try {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
 
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $client_email,  
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $package->description,
                    ],
                    'unit_amount' => intval($request->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['package_id' => $package->id]),
            'cancel_url' => route('payment.cancel'),
        ]);

        return response()->json([
            'success' => true,
            'payment_url' => $session->url,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function paymentSuccess($package_id)
{
    $package = PackageModel::find($package_id);

    if ($package) {
        $package->payment_status = 1; // You can update this based on Stripe webhook for more accuracy
        $package->save();
    }

    return redirect()->route('customer.packages')->with('success', 'Payment successful!');
}

public function paymentCancel()
{
    return redirect()->route('customer.packages')->with('error', 'Payment canceled.');
}

}
