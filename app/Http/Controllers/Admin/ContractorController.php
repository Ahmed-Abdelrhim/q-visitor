<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContractorController extends Controller
{
    public function index()
    {
        $contractors = VisitingDetails::query()
            ->with('visitor')
            ->where('is_contractor', 1)
            ->get();
        // return $contractors;
        return view('admin.ocr.contractor.index', ['contractors' => $contractors]);
    }

    public function create($visit_id)
    {
        $visit_id = decrypt($visit_id);

        $workers = Worker::query()->where('visit_id' , $visit_id)->first();
        if ($workers) {
            $notifications = array('message' => __('files.This Contractor Has Already Workers'), 'alert-type'=> 'info');
            return redirect()->back()->with($notifications);
        }
        return view('admin.visitor.contractor', ['contractor_id' => $visit_id]);
    }

    public function store(Request $request, $contractor_id)
    {
        $visit = VisitingDetails::query()->with('visitor')->find($contractor_id);
        if (!$visit) {
            $notifications = array('message' => 'This Contractor Visit Was Not Found ', 'alert-type' => 'error');
            return redirect()->back()->with($notifications);
        }
        $request = $request->except('_token');

        $length = count($request) / 2;
        DB::beginTransaction();
        for ($i = 1; $i <= $length; $i++) {
            try {
                if ($i == 1) {
                    $name = $request['name'];
                    $nat = $request['nat'];
                } else {
                    $counter = $i;
                    $name_index = 'name' . $counter;
                    $nat_index = 'nat' . $counter;
                    $name = $request[$name_index];
                    $nat = $request[$nat_index];
                }
                Worker::query()->create([
                    'name' => $name,
                    'nat_id' => $nat,
                    'visit_id' => $visit->id,
                    'visitor_id' => $visit->visitor->id,
                    'created_at' => Carbon::now(),
                ]);
            } catch (\Exception $exception) {
                // return $exception;
                $notifications = array('message' => 'Something Went Wrong ', 'alert-type' => 'error');
                return redirect()->back()->with($notifications);
            }
        }
        DB::commit();
        $visit->is_contractor = 1;
        $visit->save;
        $notifications = array('message' => 'Data Inserted Successfully', 'alert-type' => 'success');
        return redirect()->back()->with($notifications);
    }


}


// Table Workers
// big-int : id , string: name , big-int: nat_id , int: visit_id , int: visitor_id , timestamp: created_at , timestamp: updated_at


//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string|min:8',
//            'nat' => 'required|min:10',
//        ]);
//
//        if ($validator->fails()) {
//            return redirect()->back()->withErrors($validator);
//        }






//{
//    "data": {
//    "accessToken": "hpc.xksnvqy1rg2crxa77421b7kxeh179ckj",
//"expireTime": 1683108522930,
//"areaDomain": "https://ieuapi.hik-partner.com"
//},
//"errorCode": "0"
//}


