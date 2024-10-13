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
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.',
            ], 401);
        }

        // Configurer Stripe avec la clé secrète
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Récupérer les informations de paiement envoyées depuis le frontend
        $amount = $request->input('amount');  // Montant en centimes
        $paymentMethodId = $request->input('paymentMethodId');
        $tripDateId = $request->input('trip_date_id');
        $numberOfParticipants = $request->input('number_of_participants');

        DB::beginTransaction();  // Démarrer une transaction

        try {
            // Créer un PaymentIntent avec Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'http://localhost:3000/success',  // URL de redirection après paiement
            ]);

            // Si le paiement est confirmé, créer la réservation
            if ($paymentIntent->status == 'succeeded') {
                $tripDate = TripDate::findOrFail($tripDateId);

                // Vérifier s'il reste de la place pour le nombre de participants
                $currentParticipants = Reservation::where('trip_date_id', $tripDateId)
                    ->where('status', 'confirmed')
                    ->sum('number_of_participants');

                if ($currentParticipants + $numberOfParticipants > $tripDate->max_participants) {
                    return response()->json(['error' => 'Le nombre maximum de participants est atteint.'], 400);
                }

                // Créer la réservation après le paiement réussi
                $reservation = Reservation::create([
                    'user_id' => auth()->id(),  // ID de l'utilisateur authentifié
                    'trip_date_id' => $tripDateId,
                    'number_of_participants' => $numberOfParticipants,
                    'total_price' => $amount / 100,  // Convertir en euros
                    'payment_status' => 'paid',
                ]);

                DB::commit();  // Confirmer la transaction

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement et réservation réussis.',
                    'reservation' => $reservation,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Paiement non confirmé.'], 400);

        } catch (\Exception $e) {
            DB::rollBack();  // Annuler la transaction en cas d'échec
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du paiement : ' . $e->getMessage(),
            ], 500);
        }
    }
}
