<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OdgovorController extends Controller
{
    public function uspesno($podaci, $poruka = "Uspesno")
    {
        return response()->json([
            'uspesno' => true,
            'poruka' => $poruka,
            'podaci' => $podaci
        ], 200);
    }

    public function neuspesno($greske, $poruka = "Neuspesno", $status = 400)
    {
        return response()->json([
            'uspesno' => false,
            'poruka' => $poruka,
            'greske' => $greske
        ], $status);
    }
}
