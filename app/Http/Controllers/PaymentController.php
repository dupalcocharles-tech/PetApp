<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $secretKey = 'sk_test_XHUStvHY4k7pFWBskJUWFt8Y';

    public function checkout($appointmentId)
    {
        $appointment = Appointment::with(['service', 'pets'])->findOrFail($appointmentId);

        if ($appointment->pet_owner_id !== Auth::guard('pet_owner')->id()) {
            abort(403);
        }

        if ($appointment->payment_status === 'paid' || $appointment->payment_status === 'downpayment_paid') {
            return redirect()->back()->with('error', 'Payment already processed.');
        }

        // Redirect 'online' payments to dashboard/details for manual QR payment and receipt upload
        if ($appointment->payment_method === 'online') {
             return redirect()->route('pet_owner.dashboard')->with('info', 'Please view the appointment details to see the QR code and upload your payment receipt.');
        }

        // Only allow payment if the appointment is approved (accepted)
        if ($appointment->status !== 'approved' && $appointment->payment_method !== 'online') {
            return redirect()->back()->with('error', 'You can only pay the downpayment after the appointment is accepted by the clinic.');
        }

        try {
            // Downpayment amount calculation (50% of service price * number of pets, minimum 50 PHP)
            $basePrice = $appointment->service->price ?? 0;
            
            // Count pets from pivot table directly to ensure accuracy
            $petCount = DB::table('appointment_pet')->where('appointment_id', $appointment->id)->count();
            
            // Fallback: if no pets in pivot, check if singular pet_id exists (backward compatibility)
            if ($petCount === 0 && $appointment->pet_id) {
                 $petCount = 1;
            }
            
            if ($petCount < 1) $petCount = 1; // Fallback
            
            $totalPrice = $basePrice * $petCount;
            
            $isFullPayment = $appointment->payment_option === 'full';
            $amountToPay = 0;
            $description = '';

            if ($isFullPayment) {
                $amountToPay = $totalPrice;
                $description = 'Full Payment for ' . ($appointment->service->name ?? 'Service') . ($petCount > 1 ? " ($petCount pets)" : "");
            } else {
                $downpaymentAmount = $totalPrice * 0.50;
                if ($downpaymentAmount < 100) {
                    $downpaymentAmount = 100; // Minimum valid amount for PayMongo
                }
                // Ensure amount is not greater than full price if full price is small
                if ($totalPrice > 0 && $downpaymentAmount > $totalPrice) {
                    $downpaymentAmount = $totalPrice;
                }
                $amountToPay = $downpaymentAmount;
                $description = 'Downpayment for ' . ($appointment->service->name ?? 'Service') . ($petCount > 1 ? " ($petCount pets)" : "");
            }

            $amountInCents = (int)($amountToPay * 100);

            $data = [
                "data" => [
                    "attributes" => [
                        "line_items" => [
                            [
                                "name" => $description,
                                "amount" => $amountInCents,
                                "currency" => "PHP",
                                "quantity" => 1
                            ]
                        ],
                        "payment_method_types" => ["card", "gcash", "paymaya", "grab_pay", "dob", "billease"],
                        "success_url" => route('payment.success', ['appointment_id' => $appointment->id]),
                        "cancel_url" => route('payment.cancel'),
                        "description" => "Appointment ID: " . $appointment->id
                    ]
                ]
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post('https://api.paymongo.com/v1/checkout_sessions', $data);

            if ($response->successful()) {
                $result = $response->json();
                $checkoutUrl = $result['data']['attributes']['checkout_url'];
                $sessionId = $result['data']['id'];

                // Save session ID to verify later
                $appointment->transaction_id = $sessionId;
                $appointment->save();

                return redirect($checkoutUrl);
            } else {
                return redirect()->back()->with('error', 'Payment initialization failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $appointmentId = $request->query('appointment_id');

        if (!$appointmentId) {
             return redirect()->route('pet_owner.dashboard')->with('error', 'Invalid payment return url.');
        }
        
        $appointment = Appointment::findOrFail($appointmentId);
        
        if (!$appointment->transaction_id) {
             return redirect()->route('pet_owner.dashboard')->with('error', 'No transaction ID found for this appointment.');
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get('https://api.paymongo.com/v1/checkout_sessions/' . $appointment->transaction_id);
            
            if ($response->successful()) {
                $session = $response->json()['data'];
                $paymentStatus = $session['attributes']['payment_status'] ?? null; // unpai, paid
                $payments = $session['attributes']['payments'] ?? [];

                if ($paymentStatus === 'paid' || count($payments) > 0) {
                     // Check if actually paid (sometimes status takes time, but payments array usually has content)
                     // PayMongo checkout session 'payment_status' becomes 'paid' after successful payment.
                     
                     $isPaid = ($paymentStatus === 'paid');
                     
                     if (!$isPaid) {
                         foreach ($payments as $payment) {
                             if (isset($payment['attributes']['status']) && $payment['attributes']['status'] === 'paid') {
                                 $isPaid = true;
                                 break;
                             }
                         }
                     }

                     if ($isPaid) {
                        $amountPaid = 0;
                        if (isset($session['attributes']['line_items'][0]['amount'])) {
                            $amountPaid = $session['attributes']['line_items'][0]['amount'] / 100;
                        }

                        $appointment->paid_amount = $amountPaid;
                        $appointment->payment_status = ($appointment->payment_option === 'full') ? 'paid' : 'downpayment_paid'; 
                        $appointment->save();
                        
                        return redirect()->route('pet_owner.dashboard')->with('success', 'Payment successful!');
                     }
                }
            } else {
                 return redirect()->route('pet_owner.dashboard')->with('error', 'Payment verification failed: ' . $response->body());
            }

        } catch (\Exception $e) {
             return redirect()->route('pet_owner.dashboard')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        return redirect()->route('pet_owner.dashboard')->with('error', 'Payment not completed or pending.');
    }

    public function cancel()
    {
        return redirect()->route('pet_owner.dashboard')->with('info', 'Payment cancelled.');
    }

    public function uploadReceipt(Request $request, $appointmentId)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->pet_owner_id !== Auth::guard('pet_owner')->id()) {
            abort(403);
        }

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($appointment->payment_receipt && Storage::disk('public')->exists('payment_receipts/' . $appointment->payment_receipt)) {
                Storage::disk('public')->delete('payment_receipts/' . $appointment->payment_receipt);
            }

            $file = $request->file('receipt');
            $filename = time() . '_' . $appointment->id . '_receipt.' . $file->getClientOriginalExtension();
            $file->storeAs('payment_receipts', $filename, 'public');

            $appointment->payment_receipt = $filename;
            
            // Automatically set payment status based on option
            $appointment->payment_status = ($appointment->payment_option === 'full') ? 'paid' : 'downpayment_paid';
            
            $appointment->save();

            return back()->with('success', 'Receipt uploaded successfully! Payment status updated.');
        }

        return back()->with('error', 'Please select a valid image file.');
    }
}
