<?php

namespace App\Http\Controllers;

use App\Http\Resources\LokacijaResource;
use App\Models\Lokacija;
use Illuminate\Http\Request;

class LokacijaController extends OdgovorController
{
    public function index()
    {
        $lokacija = Lokacija::all();

        return $this->uspesno(LokacijaResource::collection($lokacija), "Uspesno ucitane lokacije");
    }
}
