<?php

namespace App\Http\Controllers;

use App\Http\Resources\LokacijaResource;
use App\Models\Lokacija;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class LokacijaController extends OdgovorController
{
    #[OA\Get(
        path: "/api/lokacije",
        description: "Vraca listu svih lokacija",
        summary: "Get lista lokacija",
        tags: ["Lokacija"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Uspesno vracene lokacije",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Lokacija")
                )
            )
        ]
    )]
    public function index()
    {
        $lokacija = Lokacija::all();

        return $this->uspesno(LokacijaResource::collection($lokacija), "Uspesno ucitane lokacije");
    }
}
