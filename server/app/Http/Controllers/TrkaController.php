<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrkaResource;
use App\Http\Resources\UcesceResource;
use App\Models\Trka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class TrkaController extends OdgovorController
{
    #[OA\Get(
        path: "/api/trke",
        description: "Vraca listu svih trka",
        summary: "Get lista trka",
        tags: ["Trka"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracene trke",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Trka")
                )
            )
        ]
    )]
    public function index()
    {
        $trke = Trka::all();
        return $this->uspesno(TrkaResource::collection($trke->load('lokacija')), "Uspesno ucitane trke");
    }

    #[OA\Get(
        path: "/api/trke/buduce",
        description: "Vraca listu buducih trka",
        summary: "Get lista buducih trka",
        tags: ["Trka"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracene buduce trke",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Trka")
                )
            )
        ]
    )]
    public function buduceTrke(Request $request)
    {
        $trke = Trka::where('datum', '>', now())->get();
        return $this->uspesno(TrkaResource::collection($trke->load('lokacija')), "Uspesno ucitane buduce trke");
    }

    #[OA\Post(
        path: "/api/trke",
        description: "Kreira novu trku",
        summary: "Kreiranje trke",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["naziv", "datum", "lokacija_id", "godina", "organizator", "kilometraza"],
                properties: [
                    new OA\Property(property: "naziv", type: "string", example: "Beogradski maraton"),
                    new OA\Property(property: "datum", type: "string", format: "date", example: "2026-05-15"),
                    new OA\Property(property: "lokacija_id", type: "integer", example: 1),
                    new OA\Property(property: "godina", type: "integer", example: 2026),
                    new OA\Property(property: "organizator", type: "string", example: "Atletski savez"),
                    new OA\Property(property: "kilometraza", type: "number", format: "float", example: 42.195)
                ]
            )
        ),
        tags: ["Trka"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Trka uspesno kreirana",
                content: new OA\JsonContent(ref: "#/components/schemas/Trka")
            ),
            new OA\Response(response: 422, description: "Validaciona greska"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'naziv' => 'required|string|max:255',
            'datum' => 'required|date',
            'lokacija_id' => 'required|numeric|exists:lokacije,id',
            'godina' => 'required|integer',
            'organizator' => 'required|string|max:255',
            'kilometraza' => 'required|decimal:0,2',
        ]);
        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $trka = Trka::create($request->all())->load('lokacija');
        return $this->uspesno(new TrkaResource($trka), "Trka uspesno kreirana");
    }

    #[OA\Get(
        path: "/api/trke/{id}",
        description: "Vraca detalje jedne trke",
        summary: "Get trka po ID-u",
        tags: ["Trka"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID trke",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracena trka",
                content: new OA\JsonContent(ref: "#/components/schemas/Trka")
            ),
            new OA\Response(response: 404, description: "Trka nije pronadjena")
        ]
    )]
    public function show($id)
    {
        $trka = Trka::find($id);
        if (!$trka) {
            return $this->neuspesno([], "Trka nije pronadjena", 404);
        }
        $trka->load('lokacija');
        return $this->uspesno(new TrkaResource($trka), "Uspesno ucitana trka");
    }

    #[OA\Delete(
        path: "/api/trke/{id}",
        description: "Brise trku (samo admin)",
        summary: "Brisanje trke",
        security: [["sanctum" => []]],
        tags: ["Trka"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID trke",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Trka uspesno obrisana"),
            new OA\Response(response: 404, description: "Trka nije pronadjena"),
            new OA\Response(response: 401, description: "Neautorizovan pristup"),
            new OA\Response(response: 403, description: "Zabranjen pristup")
        ]
    )]
    public function destroy($id)
    {
        $trka = Trka::find($id);
        if (!$trka) {
            return $this->neuspesno([], "Trka nije pronadjena", 404);
        }
        $trka->delete();
        return $this->uspesno([], "Trka uspesno obrisana");
    }

    #[OA\Get(
        path: "/api/trke/{id}/ucesca",
        description: "Vraca sva ucesca za odredjenu trku",
        summary: "Get ucesca za trku",
        security: [["sanctum" => []]],
        tags: ["Trka"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID trke",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracena ucesca za trku",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Ucesce")
                )
            ),
            new OA\Response(response: 404, description: "Trka nije pronadjena"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function ucesca($id)
    {
        $trka = Trka::find($id);
        if (!$trka) {
            return $this->neuspesno([], "Trka nije pronadjena", 404);
        }
        $ucesca = $trka->ucesca()->with(['trkac','trka'])->get();
        return $this->uspesno(UcesceResource::collection($ucesca), "Uspesno ucitana ucesca za trku");
    }
}
