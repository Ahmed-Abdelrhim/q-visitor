<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index()
    {
        return view('admin.quality.scan');
    }

    public function scanQr(Request $request)
    {
        $code = $request->qr_code;
        if (!is_numeric($code)) {
            return response()->json('Not Valid Qr Code');
        }
        $visit = VisitingDetails::query()->find($code);

        if (!$visit) {
            return response()->json('الزيارة غير موجودة');
        }

        if ($visit->car_type != 'T') {
            return response()->json('This Visit Is Not Of Type Truck');
        }


        if ( empty($visit->shipment_id) ) {
            return 'This Visit Does Not Have Shipment ID';
        }

        if ( empty($visit->shipment_number) ) {
            return 'This Visit Does Not Have Shipment Number';
        }

        return response()->json(['status' => 200 , 'data' =>  $visit->id]);
    }
}
