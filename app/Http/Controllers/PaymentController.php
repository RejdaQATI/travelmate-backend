<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Reservation;
use App\Models\TripDate;
use DB;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.',
            ], 401);
        }
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $amount = $request->input('amount');  
        $paymentMethodId = $request->input('paymentMethodId');
        $tripDateId = $request->input('trip_date_id');
        $numberOfParticipants = $request->input('number_of_participants');

        DB::beginTransaction();  

        try {

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'http://localhost:3000/success',  
            ]);
            if ($paymentIntent->status == 'succeeded') {
                $tripDate = TripDate::findOrFail($tripDateId);
                $currentParticipants = Reservation::where('trip_date_id', $tripDateId)
                    ->where('status', 'confirmé')
                    ->sum('number_of_participants');

                if ($currentParticipants + $numberOfParticipants > $tripDate->max_participants) {
                    return response()->json(['error' => 'Le nombre maximum de participants est atteint.'], 400);
                }

                $reservation = Reservation::create([
                    'user_id' => auth()->id(),  
                    'trip_date_id' => $tripDateId,
                    'number_of_participants' => $numberOfParticipants,
                    'total_price' => $amount / 100,  
                    'payment_status' => 'payé',
                ]);

                DB::commit();  

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement et réservation réussis.',
                    'reservation' => $reservation,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Paiement non confirmé.'], 400);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du paiement : ' . $e->getMessage(),
            ], 500);
        }
    }
}
