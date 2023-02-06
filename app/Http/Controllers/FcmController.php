<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FcmToken;

class FcmController extends Controller
{
    public function token(Request $request) {
        FcmToken::updateOrCreate(['token' => $request->get('token')]);

        return response()->json(['status' => 'success', 'code' => 200], 200);
    }
}
