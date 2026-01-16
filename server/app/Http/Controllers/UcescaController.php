<?php

namespace App\Http\Controllers;

use App\Http\Resources\UcesceResource;
use App\Models\Ucesce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UcescaController extends OdgovorController
{
    public function index()
    {
        $ucesca = Ucesce::all();
        return $this->uspesno(UcesceResource::collection($ucesca->load(['trka', 'trkac'])), "Uspesno ucitana ucesca");
    }

    public function show($id)
    {
        $ucesce = Ucesce::find($id);
        if (!$ucesce) {
            return $this->neuspesno([], "Ucesce nije pronadjeno", 404);
        }
        return $this->uspesno(new UcesceResource($ucesce->load(['trka', 'trkac'])), "Uspesno ucitano ucesce");
    }

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

    public function destroy($id)
    {
        $ucesce = Ucesce::find($id);
        if (!$ucesce) {
            return $this->neuspesno([], "Ucesce nije pronadjeno", 404);
        }
        $ucesce->delete();
        return $this->uspesno([], "Ucesce uspesno obrisano");
    }

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

    public function pretragaPoKorisniku($userId)
    {
        $ucesca = Ucesce::where('user_id', $userId)->with(['trka', 'trkac'])->get();
        return $this->uspesno(UcesceResource::collection($ucesca), "Uspesno ucitana ucesca za korisnika");
    }
}
