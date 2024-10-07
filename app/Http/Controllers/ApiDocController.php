<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Travel Mate API",
 *     version="1.0.0",
 *     description="API documentation for the Travel Mate project",
 *     @OA\Contact(
 *         email="support@travelmate.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local development server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiDocController extends Controller
{

}
