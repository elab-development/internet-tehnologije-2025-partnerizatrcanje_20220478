<?php

namespace App\Http\Controllers;

use App\Http\Resources\KomentarResource;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class KomentarController extends OdgovorController
{
    #[OA\Get(
        path: "/api/komentari",
        description: "Vraca listu svih komentara",
        summary: "Get lista komentara",
        security: [["sanctum" => []]],
        tags: ["Komentar"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vraceni komentari",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Komentar")
                )
            ),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function index()
    {
        $komentari = Komentar::with(['post', 'user'])->get();
        return $this->uspesno(KomentarResource::collection($komentari), "Uspesno ucitani komentari");
    }

    #[OA\Post(
        path: "/api/komentari",
        description: "Kreira novi komentar",
        summary: "Kreiranje komentara",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["post_id", "user_id", "komentar", "ocena", "datum_komentara"],
                properties: [
                    new OA\Property(property: "post_id", type: "integer", example: 1),
                    new OA\Property(property: "user_id", type: "integer", example: 1),
                    new OA\Property(property: "komentar", type: "string", example: "Ovo je komentar"),
                    new OA\Property(property: "ocena", type: "number", minimum: 0, maximum: 5, example: 4),
                    new OA\Property(property: "datum_komentara", type: "string", format: "date", example: "2026-02-23")
                ]
            )
        ),
        tags: ["Komentar"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Komentar uspesno kreiran",
                content: new OA\JsonContent(ref: "#/components/schemas/Komentar")
            ),
            new OA\Response(response: 422, description: "Validaciona greska"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|numeric|exists:postovi,id',
            'user_id' => 'required|numeric|exists:users,id',
            'komentar' => 'required|string',
            'ocena' => 'required|numeric|min:0|max:5',
            'datum_komentara' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $komentar = Komentar::create($request->all())->load(['post', 'user']);
        return $this->uspesno(new KomentarResource($komentar), "Komentar uspesno kreiran");
    }

    #[OA\Delete(
        path: "/api/komentari/{id}",
        description: "Brise komentar",
        summary: "Brisanje komentara",
        security: [["sanctum" => []]],
        tags: ["Komentar"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID komentara",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Komentar uspesno obrisan"),
            new OA\Response(response: 404, description: "Komentar nije pronadjen"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function destroy($id)
    {
        $komentar = Komentar::find($id);
        if (!$komentar) {
            return $this->neuspesno([], "Komentar nije pronadjen", 404);
        }
        $komentar->delete();
        return $this->uspesno([], "Komentar uspesno obrisan");
    }

    #[OA\Get(
        path: "/api/komentari/{postId}",
        description: "Vraca sve komentare za odredjeni post",
        summary: "Get komentari po postu",
        security: [["sanctum" => []]],
        tags: ["Komentar"],
        parameters: [
            new OA\Parameter(
                name: "postId",
                in: "path",
                required: true,
                description: "ID posta",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vraceni komentari za post",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Komentar")
                )
            ),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function pretraziPoPostu($postId)
    {
        $komentari = Komentar::where('post_id', $postId)->with(['post', 'user'])->orderBy('datum_komentara', 'desc')->get();
        return $this->uspesno(KomentarResource::collection($komentari), "Uspesno ucitani komentari za post");
    }
}
