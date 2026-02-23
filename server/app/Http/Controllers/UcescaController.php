<?php

namespace App\Http\Controllers;

use App\Http\Resources\UcesceResource;
use App\Models\Ucesce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class UcescaController extends OdgovorController
{
    #[OA\Get(
        path: "/api/ucesca",
        description: "Vraca listu svih ucesca",
        summary: "Get lista ucesca",
        security: [["sanctum" => []]],
        tags: ["Ucesce"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracena ucesca",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Ucesce")
                )
            ),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function index()
    {
        $ucesca = Ucesce::all();
        return $this->uspesno(UcesceResource::collection($ucesca->load(['trka', 'trkac'])), "Uspesno ucitana ucesca");
    }

    #[OA\Get(
        path: "/api/ucesca/{id}",
        description: "Vraca detalje jednog ucesca",
        summary: "Get ucesce po ID-u",
        security: [["sanctum" => []]],
        tags: ["Ucesce"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID ucesca",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vraceno ucesce",
                content: new OA\JsonContent(ref: "#/components/schemas/Ucesce")
            ),
            new OA\Response(response: 404, description: "Ucesce nije pronadjeno"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function show($id)
    {
        $ucesce = Ucesce::find($id);
        if (!$ucesce) {
            return $this->neuspesno([], "Ucesce nije pronadjeno", 404);
        }
        return $this->uspesno(new UcesceResource($ucesce->load(['trka', 'trkac'])), "Uspesno ucitano ucesce");
    }

    #[OA\Post(
        path: "/api/ucesca",
        description: "Kreira novo ucesce",
        summary: "Kreiranje ucesca",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["user_id", "trka_id", "vreme"],
                properties: [
                    new OA\Property(property: "user_id", type: "integer", example: 1),
                    new OA\Property(property: "trka_id", type: "integer", example: 1),
                    new OA\Property(property: "vreme", type: "number", example: 3600)
                ]
            )
        ),
        tags: ["Ucesce"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ucesce uspesno kreirano",
                content: new OA\JsonContent(ref: "#/components/schemas/Ucesce")
            ),
            new OA\Response(response: 422, description: "Validaciona greska"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'trka_id' => 'required|numeric|exists:trke,id',
            'vreme' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $ucesce = Ucesce::create($request->all())->load(['trka', 'trkac']);
        return $this->uspesno(new UcesceResource($ucesce), "Ucesce uspesno kreirano");
    }

    #[OA\Delete(
        path: "/api/ucesca/{id}",
        description: "Brise ucesce",
        summary: "Brisanje ucesca",
        security: [["sanctum" => []]],
        tags: ["Ucesce"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID ucesca",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Ucesce uspesno obrisano"),
            new OA\Response(response: 404, description: "Ucesce nije pronadjeno"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function destroy($id)
    {
        $ucesce = Ucesce::find($id);
        if (!$ucesce) {
            return $this->neuspesno([], "Ucesce nije pronadjeno", 404);
        }
        $ucesce->delete();
        return $this->uspesno([], "Ucesce uspesno obrisano");
    }

    #[OA\Get(
        path: "/api/ucesca/paginacija",
        description: "Vraca ucesca sa paginacijom",
        summary: "Get ucesca sa paginacijom",
        security: [["sanctum" => []]],
        tags: ["Ucesce"],
        parameters: [
            new OA\Parameter(
                name: "poStranici",
                in: "query",
                required: false,
                description: "Broj ucesca po stranici",
                schema: new OA\Schema(type: "integer", default: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracena ucesca sa paginacijom"
            ),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function paginacija(Request $request)
    {
        $poStranici = 10;
        if ($request->has('poStranici')) {
            $poStranici = (int) $request->query('poStranici');
        }

        $ucesca = DB::table('ucesca')
            ->join('trke', 'ucesca.trka_id', '=', 'trke.id')
            ->join('users', 'ucesca.user_id', '=', 'users.id')
            ->select('ucesca.*', 'trke.naziv as trka_naziv', 'users.name as user_name')
            ->paginate($poStranici);

        return $this->uspesno($ucesca, "Uspesno ucitana ucesca sa paginacijom");
    }

    #[OA\Get(
        path: "/api/users/{userId}/ucesca",
        description: "Vraca sva ucesca za odredjenog korisnika",
        summary: "Get ucesca po korisniku",
        security: [["sanctum" => []]],
        tags: ["Ucesce"],
        parameters: [
            new OA\Parameter(
                name: "userId",
                in: "path",
                required: true,
                description: "ID korisnika",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracena ucesca za korisnika",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Ucesce")
                )
            ),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function pretragaPoKorisniku($userId)
    {
        $ucesca = Ucesce::where('user_id', $userId)->with(['trka', 'trkac'])->get();
        return $this->uspesno(UcesceResource::collection($ucesca), "Uspesno ucitana ucesca za korisnika");
    }

    #[OA\Get(
        path: "/api/trke/{trkaId}/ucesca",
        description: "Vraca sva ucesca za odredjenu trku",
        summary: "Get ucesca po trci",
        security: [["sanctum" => []]],
        tags: ["Ucesce"],
        parameters: [
            new OA\Parameter(
                name: "trkaId",
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
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function pretragaPoTrci($trkaId)
    {
        $ucesca = Ucesce::where('trka_id', $trkaId)->with(['trka', 'trkac'])->get();
        return $this->uspesno(UcesceResource::collection($ucesca), "Uspesno ucitana ucesca za trku");
    }
}
