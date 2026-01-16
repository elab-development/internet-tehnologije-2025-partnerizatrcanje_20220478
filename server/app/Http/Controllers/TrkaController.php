<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrkaResource;
use App\Http\Resources\UcesceResource;
use App\Models\Trka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrkaController extends OdgovorController
{
    public function index()
    {
        $trke = Trka::all();
        return $this->uspesno(TrkaResource::collection($trke->load('lokacija')), "Uspesno ucitane trke");
    }

    public function buduceTrke(Request $request)
    {
        $trke = Trka::where('datum', '>', now())->get();
        return $this->uspesno(TrkaResource::collection($trke->load('lokacija')), "Uspesno ucitane buduce trke");
    }

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

    public function show($id)
    {
        $trka = Trka::find($id);
        if (!$trka) {
            return $this->neuspesno([], "Trka nije pronadjena", 404);
        }
        $trka->load('lokacija');
        return $this->uspesno(new TrkaResource($trka), "Uspesno ucitana trka");
    }

    public function destroy($id)
    {
        $trka = Trka::find($id);
        if (!$trka) {
            return $this->neuspesno([], "Trka nije pronadjena", 404);
        }
        $trka->delete();
        return $this->uspesno([], "Trka uspesno obrisana");
    }

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
