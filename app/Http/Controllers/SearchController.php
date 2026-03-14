<?php

namespace App\Http\Controllers;

use App\Models\Itineraire;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // 1 - Tous les itinéraires avec leurs destinations
    public function index()
    {
        $itineraires = Itineraire::with(['destinations.items', 'user'])->get();
        return response()->json($itineraires);
    }

    // 2 - Filtrer par catégorie et durée
    public function filter(Request $request)
    {
        $query = Itineraire::with(['destinations.items', 'user']);

        if ($request->has('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->has('duree')) {
            $query->where('duree', $request->duree);
        }

        return response()->json($query->get());
    }

    // 3 - Recherche par mot-clé dans le titre
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $itineraires = Itineraire::with(['destinations.items', 'user'])
            ->where('titre', 'LIKE', '%' . $keyword . '%')
            ->get();

        return response()->json($itineraires);
    }

    // 4 - Itinéraires les plus populaires
    public function popular()
    {
        $itineraires = Itineraire::with(['destinations.items', 'user'])
            ->withCount('favoris')
            ->orderBy('favoris_count', 'desc')
            ->get();

        return response()->json($itineraires);
    }

    // 5 - Statistiques : nombre d'itinéraires par catégorie
    public function statsByCategorie()
    {
        $stats = Itineraire::select('categorie')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('categorie')
            ->get();

        return response()->json($stats);
    }

    // 6 - Statistiques : nombre d'utilisateurs inscrits par mois
    public function statsByMonth()
    {
        $stats = User::selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total')
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->get();

        return response()->json($stats);
    }
}
