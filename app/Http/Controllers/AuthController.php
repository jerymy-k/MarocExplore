<?php
/**
 * @OA\Info(
 *     title="MarocExplore API",
 *     version="1.0.0",
 *     description="API pour la plateforme MarocExplore"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Créer un compte",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Compte créé avec succès"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

        return response()->json(
            [
                'message' => 'Compte créé avec succès',
                'user' => $user,
                'token' => $token,
            ],
            201,
        );
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Se connecter",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Connecté avec succès"),
     *     @OA\Response(response=401, description="Email ou mot de passe incorrect")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!($token = auth()->attempt($credentials))) {
            return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
        }

        return response()->json([
            'message' => 'Connecté avec succès',
            'token' => $token,
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Se déconnecter",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Déconnecté avec succès")
     * )
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Déconnecté avec succès']);
    }
    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Voir son profil",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Profil de l'utilisateur")
     * )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
}
