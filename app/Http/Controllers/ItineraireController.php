<?php

namespace App\Http\Controllers;

use App\Models\Itineraire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItineraireController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/itineraires",
     *     summary="Voir tous les itinéraires",
     *     tags={"Itinéraires"},
     *     @OA\Response(response=200, description="Liste des itinéraires")
     * )
     */
    public function index()
    {
        $itineraires = Itineraire::with(['destinations.items', 'user'])->get();
        return response()->json($itineraires);
    }
    /**
     * @OA\Get(
     *     path="/api/itineraires/{id}",
     *     summary="Voir un itinéraire",
     *     tags={"Itinéraires"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Itinéraire trouvé"),
     *     @OA\Response(response=404, description="Itin éraire non trouvé")
     * )
     */
    public function show($id)
    {
        $itineraire = Itineraire::with(['destinations.items', 'user'])->find($id);
        if (!$itineraire) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }
        return response()->json($itineraire);
    }
    /**
 * @OA\Post(
 *     path="/api/itineraires",
 *     summary="Créer un itinéraire",
 *     tags={"Itinéraires"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"titre","categorie","duree","image","destinations"},
 *                 @OA\Property(property="titre", type="string"),
 *                 @OA\Property(property="categorie", type="string"),
 *                 @OA\Property(property="duree", type="integer"),
 *                 @OA\Property(property="image", type="string", format="binary")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=201, description="Itinéraire créé avec succès"),
 *     @OA\Response(response=401, description="Non authentifié"),
 *     @OA\Response(response=422, description="Erreur de validation")
 * )
 */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'categorie' => 'required|string',
            'duree' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'destinations' => 'required|array|min:2',
            'destinations.*.nom' => 'required|string',
            'destinations.*.logement' => 'required|string',
            'destinations.*.items' => 'required|array|min:1',
            'destinations.*.items.*.nom' => 'required|string',
            'destinations.*.items.*.type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = $request->file('image')->store('itineraires', 'public');

        $itineraire = Itineraire::create([
            'titre' => $request->titre,
            'categorie' => $request->categorie,
            'duree' => $request->duree,
            'image' => $imagePath,
            'user_id' => auth()->id(),
        ]);

        foreach ($request->destinations as $dest) {
            $destination = $itineraire->destinations()->create([
                'nom' => $dest['nom'],
                'logement' => $dest['logement'],
            ]);

            foreach ($dest['items'] as $item) {
                $destination->items()->create([
                    'nom' => $item['nom'],
                    'type' => $item['type'],
                ]);
            }
        }

        return response()->json(
            [
                'message' => 'Itinéraire créé avec succès',
                'itineraire' => $itineraire->load('destinations.items'),
            ],
            201,
        );
    }
/**
 * @OA\Put(
 *     path="/api/itineraires/{id}",
 *     summary="Modifier un itinéraire",
 *     tags={"Itinéraires"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="titre", type="string"),
 *             @OA\Property(property="categorie", type="string"),
 *             @OA\Property(property="duree", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Itinéraire modifié avec succès"),
 *     @OA\Response(response=403, description="Non autorisé"),
 *     @OA\Response(response=404, description="Itinéraire non trouvé")
 * )
 */
    public function update(Request $request, $id)
    {
        $itineraire = Itineraire::find($id);

        if (!$itineraire) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        if ($itineraire->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $itineraire->update($request->only(['titre', 'categorie', 'duree']));

        return response()->json([
            'message' => 'Itinéraire modifié avec succès',
            'itineraire' => $itineraire,
        ]);
    }
/**
 * @OA\Delete(
 *     path="/api/itineraires/{id}",
 *     summary="Supprimer un itinéraire",
 *     tags={"Itinéraires"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Itinéraire supprimé avec succès"),
 *     @OA\Response(response=403, description="Non autorisé"),
 *     @OA\Response(response=404, description="Itinéraire non trouvé")
 * )
 */
    public function destroy($id)
    {
        $itineraire = Itineraire::find($id);

        if (!$itineraire) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        if ($itineraire->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $itineraire->delete();

        return response()->json(['message' => 'Itinéraire supprimé avec succès']);
    }
}
