<?php

namespace App\Presentation\Docs;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'TSG Clean API',
    description: 'API de ejemplo con JWT y Clean Architecture'
)]
#[OA\Server(
    url: '/api/v1',
    description: 'Servidor local'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class OpenApi {}
