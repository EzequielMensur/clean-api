<?php

namespace App\Presentation\Docs;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth', description: 'Autenticación con JWT')]
final class AuthDoc
{
    #[OA\Post(
        path: '/auth/login',
        tags: ['Auth'],
        summary: 'Login con email y password',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ada@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'Secret123!'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK (Set-Cookie: access_token)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'ok'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 900),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Credenciales inválidas', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'message', type: 'string', example: 'Credenciales inválidas')]
            )),
        ]
    )]
    public function loginDoc(): void {}

    #[OA\Post(
        path: '/auth/refresh',
        tags: ['Auth'],
        summary: 'Refresca el JWT (lee cookie access_token y rota el token)',
        parameters: [
            // Para clientes que no envíen Authorization, documentamos cookie:
            new OA\Parameter(
                name: 'access_token',
                description: 'JWT actual (si no se envía Authorization: Bearer)',
                in: 'cookie',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK (Set-Cookie: access_token nuevo)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'refreshed'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 900),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Token no provisto / expirado / inválido', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'message', type: 'string', example: 'Token no provisto')]
            )),
        ]
    )]
    public function refreshDoc(): void {}

    #[OA\Post(
        path: '/auth/logout',
        tags: ['Auth'],
        summary: 'Logout (invalida token y borra cookie)',
        parameters: [
            new OA\Parameter(
                name: 'access_token',
                description: 'JWT actual (si no se envía Authorization: Bearer)',
                in: 'cookie',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK (borra cookie access_token)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'ok'),
                    ]
                )
            ),
        ]
    )]
    public function logoutDoc(): void {}

    #[OA\Get(
        path: '/me',
        tags: ['Auth'],
        security: [['bearerAuth' => []]],  // opcional: Swagger enviará Authorization si lo configurás
        summary: 'Perfil del usuario autenticado',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Ada Lovelace'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ada@example.com'),
                        new OA\Property(property: 'username', type: 'string', example: 'ada'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'No autenticado', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'message', type: 'string', example: 'No autenticado')]
            )),
        ]
    )]
    public function meDoc(): void {}
}
