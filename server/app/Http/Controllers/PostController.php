<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class PostController extends OdgovorController
{
    #[OA\Get(
        path: "/api/postovi",
        description: "Vraca listu svih postova",
        summary: "Get lista postova",
        security: [["sanctum" => []]],
        tags: ["Post"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vraceni postovi",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Post")
                )
            ),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function index()
    {
        $postovi = Post::all()->load(['ucesce', 'komentari'])->sortBy(
            function ($post) {
                return $post->datum_objave;
            },
            SORT_REGULAR,
            true
        );
        return $this->uspesno(PostResource::collection($postovi), "Uspesno ucitani postovi");
    }

    #[OA\Get(
        path: "/api/postovi/{id}",
        description: "Vraca detalje jednog posta",
        summary: "Get post po ID-u",
        security: [["sanctum" => []]],
        tags: ["Post"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID posta",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracen post",
                content: new OA\JsonContent(ref: "#/components/schemas/Post")
            ),
            new OA\Response(response: 404, description: "Post nije pronadjen"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function show($id)
    {
        $post = Post::find($id)->load(['ucesce', 'komentari']);
        if (!$post) {
            return $this->neuspesno([], "Post nije pronadjen", 404);
        }
        return $this->uspesno(new PostResource($post), "Uspesno ucitan post");
    }

    #[OA\Post(
        path: "/api/postovi",
        description: "Kreira novi post",
        summary: "Kreiranje posta",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["ucesce_id", "sadrzaj", "datum_objave"],
                properties: [
                    new OA\Property(property: "ucesce_id", type: "integer", example: 1),
                    new OA\Property(property: "sadrzaj", type: "string", example: "Ovo je sadrzaj posta"),
                    new OA\Property(property: "datum_objave", type: "string", format: "date", example: "2026-02-23")
                ]
            )
        ),
        tags: ["Post"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Post uspesno kreiran",
                content: new OA\JsonContent(ref: "#/components/schemas/Post")
            ),
            new OA\Response(response: 422, description: "Validaciona greska"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ucesce_id' => 'required|numeric|exists:ucesca,id',
            'sadrzaj' => 'required|string',
            'datum_objave' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $post = Post::create($request->all())->load(['ucesce', 'komentari']);
        return $this->uspesno(new PostResource($post), "Post uspesno kreiran");

    }

    #[OA\Delete(
        path: "/api/postovi/{id}",
        description: "Brise post",
        summary: "Brisanje posta",
        security: [["sanctum" => []]],
        tags: ["Post"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID posta",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Post uspesno obrisan"),
            new OA\Response(response: 404, description: "Post nije pronadjen"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->neuspesno([], "Post nije pronadjen", 404);
        }
        $post->delete();
        return $this->uspesno([], "Post uspesno obrisan");
    }
}
