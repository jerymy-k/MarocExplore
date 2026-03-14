<?php

namespace App\Http\Controllers;
use App\Models\Favori;
use App\Models\Itineraire;

class FavorisController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/itineraires/{id}/favori",
 *     summary="Ajouter ou retirer des favoris",
 *     tags={"Itinéraires"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Ajouté ou retiré des favoris"),
 *     @OA\Response(response=404, description="Itinéraire non trouvé")
 * )
 */
    public function store($id)
    {
        $itineraire = Itineraire::find($id);

        if (!$itineraire) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        $existingFavori = Favori::where('user_id', auth()->id())
            ->where('itineraire_id', $id)
            ->first();

        if ($existingFavori) {
            $existingFavori->delete();
            return response()->json(['message' => 'Retiré des favoris']);
        }

        Favori::create([
            'user_id' => auth()->id(),
            'itineraire_id' => $id,
        ]);

        return response()->json(['message' => 'Ajouté aux favoris']);
    }
}
