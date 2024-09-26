<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    /**
     * Lister toutes les villes.
     */
// CityController.php

public function index()
{
    $cities = City::with('trip')->get(); 
    return response()->json(['cities' => $cities]);
}



    public function show($id)
    {
        $city = City::with('trips')->find($id);

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'Ville non trouvée',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'city' => $city,
        ]);
    }

}
