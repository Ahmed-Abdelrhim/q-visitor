<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index()
    {
        return view('admin.quality.scan');
    }

    public function scanQr(Request $request)
    {
        return response()->json($request->qr_code);
        // return  $request->qr_code;
    }
}
