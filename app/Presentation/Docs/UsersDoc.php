<?php

namespace App\Presentation\Docs;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users', description: 'Operaciones de usuarios')]
final class UsersDoc
{
    #[OA\Post(
        path: '/user/register',
        tags: ['Users'],
        summary: 'Registro público de un usuario',
        operationId: 'registerUser',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Ada Lovelace'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ada@example.com'),
                    new OA\Property(property: 'username', type: 'string', example: 'ada', nullable: true),
                    new OA\Property(property: 'password', type: 'string', minLength: 8, example: 'Secret123!'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'Secret123!'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Usuario creado',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Ada Lovelace'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ada@example.com'),
                        new OA\Property(property: 'username', type: 'string', example: 'ada', nullable: true),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validación fallida',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The email has already been taken.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function registerDoc(): void {}

    #[OA\Get(
        path: '/users',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        summary: 'Listado paginado de usuarios',
        operationId: 'listUsers',
        parameters: [
            new OA\Parameter(name: 'q', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', maximum: 100, minimum: 1)),
            new OA\Parameter(name: 'sort', in: 'query', schema: new OA\Schema(type: 'string', enum: ['-id', 'name'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                                    new OA\Property(property: 'username', type: 'string', nullable: true),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'per_page', type: 'integer', example: 10),
                                new OA\Property(property: 'total', type: 'integer', example: 42),
                                new OA\Property(property: 'last_page', type: 'integer', example: 5),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'No autorizado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'No autenticado')],
                    type: 'object'
                )
            ),
        ]
    )]
    public function listUsersDoc(): void {}

    #[OA\Get(
        path: '/users/{id}',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        summary: 'Detalle de usuario',
        operationId: 'showUser',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'username', type: 'string', nullable: true),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'No autorizado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'No autenticado')],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'No encontrado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Recurso no encontrado')],
                    type: 'object'
                )
            ),
        ]
    )]
    public function showUserDoc(): void {}

    #[OA\Put(
        path: '/users/{id}',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        summary: 'Actualizar usuario',
        operationId: 'updateUser',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 120),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'username', type: 'string', maxLength: 60, nullable: true),
                    new OA\Property(property: 'password', type: 'string', minLength: 8),
                    new OA\Property(property: 'password_confirmation', type: 'string'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Actualizado',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'username', type: 'string', nullable: true),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'No autorizado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'No autenticado')],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Prohibido (no es el dueño)',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Prohibido')],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validación fallida',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The email has already been taken.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function updateUserDoc(): void {}

    #[OA\Delete(
        path: '/users/{id}',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        summary: 'Eliminar usuario',
        operationId: 'deleteUser',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Sin contenido'),
            new OA\Response(
                response: 401,
                description: 'No autorizado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'No autenticado')],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Prohibido (no es el dueño)',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Prohibido')],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'No encontrado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Recurso no encontrado')],
                    type: 'object'
                )
            ),
        ]
    )]
    public function deleteUserDoc(): void {}
}
