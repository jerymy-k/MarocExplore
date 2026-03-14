<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="MarocExplore API",
 *     version="1.0.0",
 *     description="API pour la plateforme MarocExplore"
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Serveur local"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerController extends Controller
{
}
