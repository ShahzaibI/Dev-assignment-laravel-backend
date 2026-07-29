<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'CMS API',
    version: '1.0.0',
    description: 'Laravel CMS Assignment API — POST /api/login to get a Bearer token, then click Authorize.'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local server'
)]
class SwaggerAnnotations {}
