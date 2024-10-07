<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\TripDate;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReservationAcceptedNotification;
use OpenApi\Annotations as OA;

class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservations/user",
     *     summary="Get the current user's reservations",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of the user's reservations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="reservations", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="tripDate", type="object", 
     *                         @OA\Property(property="trip", type="object", 
     *                             @OA\Property(property="title", type="string", example="Trip to Paris")
     *                         )
     *                     ),
     *                     @OA\Property(property="status", type="string", example="confirmed")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function userReservations()
    {
        $reservations = Reservation::with('tripDate.trip')->where('user_id', auth()->id())->get();
    
        return response()->json([
            'reservations' => $reservations
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/all",
     *     summary="Get all reservations (Admin only)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of all reservations (Admin only)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="reservations", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user", type="object", 
     *                         @OA\Property(property="name", type="string", example="John Doe")
     *                     ),
     *                     @OA\Property(property="tripDate", type="object", 
     *                         @OA\Property(property="trip", type="object", 
     *                             @OA\Property(property="title", type="string", example="Trip to Paris")
     *                         )
     *                     ),
     *                     @OA\Property(property="status", type="string", example="confirmed")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function allReservations() {
        if (Auth::user()->isAdmin()) {
            $reservations = Reservation::with('tripDate.trip', 'user')->get();
            return response()->json(['reservations' => $reservations]);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Create a new reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"trip_date_id", "number_of_participants"},
     *             @OA\Property(property="trip_date_id", type="integer", example=1),
     *             @OA\Property(property="number_of_participants", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reservation created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="reservation", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="number_of_participants", type="integer", example=2),
     *                 @OA\Property(property="status", type="string", example="pending")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Maximum number of participants reached",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Le nombre maximum de participants est atteint.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'trip_date_id' => 'required|exists:trip_dates,id',
            'number_of_participants' => 'required|integer',
        ]);

        $tripDate = TripDate::findOrFail($validatedData['trip_date_id']);
        $currentParticipants = Reservation::where('trip_date_id', $tripDate->id)
            ->where('status', 'confirmed')
            ->sum('number_of_participants');
        if ($currentParticipants + $validatedData['number_of_participants'] > $tripDate->max_participants) {
            return response()->json(['error' => 'Le nombre maximum de participants est atteint.'], 400);
        }
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'trip_date_id' => $validatedData['trip_date_id'],
            'number_of_participants' => $validatedData['number_of_participants'],
            'status' => 'pending',
        ]);

        return response()->json([
            'reservation' => $reservation
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/{id}",
     *     summary="Get a specific reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="number_of_participants", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Reservation not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return response()->json([
            'reservation' => $reservation
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/{id}",
     *     summary="Update reservation status (Admin only)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="confirmed"),
     *             @OA\Property(property="payment_status", type="string", example="paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="reservation", type="object",
     *                 @OA\Property(property="status", type="string", example="confirmed")
     *             ),
     *             @OA\Property(property="message", type="string", example="La réservation a été mise à jour avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Accès refusé. Vous devez être administrateur.")
     *         )
     *     )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $validatedData = $request->validate([
            'status' => 'required|in:confirmed,cancelled,pending', 
            'payment_status' => 'nullable|in:pending,paid,failed',  
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($validatedData);
        
        if ($validatedData['status'] == 'confirmed' && $reservation->user) {
            $reservation->user->notify(new ReservationAcceptedNotification($reservation));
        }
        
        return response()->json([
            'reservation' => $reservation,
            'message' => 'La réservation a été mise à jour avec succès'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/reservations/{id}",
     *     summary="Delete a reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La réservation a été supprimée avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Reservation not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return response()->json(['message' => 'La réservation a été supprimée avec succès.']);
    }
}
