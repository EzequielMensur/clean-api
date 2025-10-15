<?php

namespace App\Presentation\Docs;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Posts', description: 'CRUD de posts')]
final class PostsDoc
{
    #[
        OA\Schema(
            schema: 'Post',
            type: 'object',
            required: ['id', 'user_id', 'title', 'body'],
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'user_id', type: 'integer', example: 10),
                new OA\Property(property: 'title', type: 'string', maxLength: 160, example: 'Mi primer post'),
                new OA\Property(property: 'body', type: 'string', example: 'Contenido del post...'),
                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
                new OA\Property(property: 'deleted_at', type: 'string', format: 'date-time', nullable: true),
            ]
        ),
        OA\Schema(
            schema: 'PostCreate',
            type: 'object',
            required: ['title', 'body'],
            properties: [
                new OA\Property(property: 'title', type: 'string', maxLength: 160, example: 'Título del post'),
                new OA\Property(property: 'body', type: 'string', example: 'Texto del post...'),
            ]
        ),
        OA\Schema(
            schema: 'PostUpdate',
            type: 'object',
            properties: [
                new OA\Property(property: 'title', type: 'string', maxLength: 160),
                new OA\Property(property: 'body', type: 'string'),
            ]
        ),
        OA\Schema(
            schema: 'ApiError',
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Detalle del error'),
            ]
        ),
        OA\Schema(
            schema: 'PostPage',
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Post')
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
            ]
        ),
    ]

    /* =========================
     *   LISTADO (GET /posts)
     * ========================= */
    #[OA\Get(
        path: '/posts',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Listado paginado de posts',
        parameters: [
            new OA\Parameter(name: 'q', description: 'Buscar en title/body', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'user_id', in: 'query', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1), example: 1),
            new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', maximum: 100, minimum: 1), example: 10),
            new OA\Parameter(
                name: 'sort',
                description: 'Campo de orden; con "-" descendente',
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: ['-id', 'id', 'title']),
                example: '-id'
            ),
            new OA\Parameter(name: 'with_trashed', in: 'query', schema: new OA\Schema(type: 'boolean'), example: false),
            new OA\Parameter(name: 'only_trashed', in: 'query', schema: new OA\Schema(type: 'boolean'), example: false),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(ref: '#/components/schemas/PostPage')
            ),
            new OA\Response(
                response: 401,
                description: 'No autorizado',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
            ),
        ]
    )]
    public function listPostsDoc(): void {}

    /* =========================
     *   DETALLE (GET /posts/{id})
     * ========================= */
    #[OA\Get(
        path: '/posts/{id}',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Detalle de un post',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(response: 401, description: 'No autorizado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'No encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ]
    )]
    public function showPostDoc(): void {}

    /* =========================
     *   CREAR (POST /posts)
     * ========================= */
    #[OA\Post(
        path: '/posts',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Crear un post (usuario autenticado)',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PostCreate')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Creado', content: new OA\JsonContent(ref: '#/components/schemas/Post')),
            new OA\Response(response: 401, description: 'No autorizado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 422, description: 'Validación fallida', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ]
    )]
    public function createPostDoc(): void {}

    /* =========================
     *   ACTUALIZAR (PUT/PATCH /posts/{id})
     * ========================= */
    #[OA\Put(
        path: '/posts/{id}',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Actualizar un post',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PostUpdate')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Post')),
            new OA\Response(response: 401, description: 'No autorizado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 403, description: 'Prohibido', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'No encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 422, description: 'Validación fallida', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ]
    )]
    public function updatePostDoc(): void {}

    #[OA\Patch(
        path: '/posts/{id}',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Actualizar parcialmente un post',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PostUpdate')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Post')),
            new OA\Response(response: 401, description: 'No autorizado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 403, description: 'Prohibido', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'No encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 422, description: 'Validación fallida', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ]
    )]
    public function patchPostDoc(): void {}

    /* =========================
     *   ELIMINAR (DELETE /posts/{id})
     * ========================= */
    #[OA\Delete(
        path: '/posts/{id}',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Eliminar un post (soft delete)',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Sin contenido'),
            new OA\Response(response: 401, description: 'No autorizado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 403, description: 'Prohibido', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'No encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ]
    )]
    public function deletePostDoc(): void {}

    #[OA\Post(
        path: '/posts/{id}/restore',
        tags: ['Posts'],
        security: [['bearerAuth' => []]],
        summary: 'Restaurar un post soft-deleted',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Restaurado', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'message', type: 'string', example: 'restored')],
                type: 'object'
            )),
            new OA\Response(response: 401, description: 'No autorizado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 403, description: 'Prohibido', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'No encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ]
    )]
    public function restorePostDoc(): void {}
}
