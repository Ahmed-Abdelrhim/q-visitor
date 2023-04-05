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
        $visit = VisitingDetails::query()->with('visitor')->find($code);

        if (!$visit) {
            // return response()->json('الزيارة غير موجودة');
            return response()->json(__('files.This Visit Does Not Exisit'));
            // return response()->json('Visit Was Not Found \'ID Of Qr Code '.$code.' \' ');
        }


        if ($visit->car_type != 'T') {
            return response()->json(__('files.This Visit Is Not Of Type Truck'));
        }


        if (empty($visit->shipment_id)) {
            return __('files.This Visit Does Not Have Shipment ID');
        }

        if (empty($visit->shipment_number)) {
            return __('files.This Visit Does Not Have Shipment Number');
        }

        return response()->json(['status' => 200, 'data' => $visit->id , 'visit' => $visit]);
    }

    public function acceptVisit()
    {
        $visit_id = 0;
        if (isset($_POST['visit_id'])) {
            $visit_id = $_POST['visit_id'];
        }

        return $visit_id;

        $visit = VisitingDetails::query()->find($visit_id);

        if (!$visit) {
            return response()->json('الزيارة غير موجودة');
        }


        if ($visit->car_type != 'T') {
            return response()->json('This Visit Is Not Of Type Truck');
        }

        if (empty($visit->shipment_id)) {
            return 'This Visit Does Not Have Shipment ID';
        }

        if (empty($visit->shipment_number)) {
            return 'This Visit Does Not Have Shipment Number';
        }

        $visit->quality_check = 2;
        $visit->save();

        return response()->json(['status' => 200, 'data' => 'quality check is now 2']);
    }

    public function rejectVisit()
    {
        $visit_id = 0;
        if (isset($_POST['visit_id'])) {
            $visit_id = $_POST['visit_id'];
        }

        $visit = VisitingDetails::query()->find($visit_id);

        if (!$visit) {
            return response()->json('الزيارة غير موجودة' );
        }

        if ($visit->car_type != 'T') {
            return response()->json('This Visit Is Not Of Type Truck');
        }

        if (empty($visit->shipment_id)) {
            return 'This Visit Does Not Have Shipment ID';
        }

        if (empty($visit->shipment_number)) {
            return 'This Visit Does Not Have Shipment Number';
        }

        $visit->quality_check = 5;
        $visit->save();

        return response()->json(['status' => 200, 'data' => 'Sucess Transaction']);
    }
}









