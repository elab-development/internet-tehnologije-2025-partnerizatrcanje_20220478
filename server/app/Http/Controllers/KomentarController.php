<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KomentarController extends OdgovorController
{
    public function index()
    {
        $komentari = \App\Models\Komentar::with(['post', 'user'])->get();
        return $this->uspesno(\App\Http\Resources\KomentarResource::collection($komentari), "Uspesno ucitani komentari");
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'post_id' => 'required|numeric|exists:posts,id',
            'user_id' => 'required|numeric|exists:users,id',
            'komentar' => 'required|string',
            'ocena' => 'required|numeric|min:1|max:5',
            'datum_komentara' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $komentar = \App\Models\Komentar::create($request->all())->load(['post', 'user']);
        return $this->uspesno(new \App\Http\Resources\KomentarResource($komentar), "Komentar uspesno kreiran");
    }

    public function destroy($id)
    {
        $komentar = \App\Models\Komentar::find($id);
        if (!$komentar) {
            return $this->neuspesno([], "Komentar nije pronadjen", 404);
        }
        $komentar->delete();
        return $this->uspesno([], "Komentar uspesno obrisan");
    }
}
