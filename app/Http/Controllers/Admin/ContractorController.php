<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ContractorController extends Controller
{
    public function index($contractor_id)
    {
        $contractor_id = decrypt($contractor_id);
        return view('admin.visitor.contractor', ['contractor_id' => $contractor_id]);
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
        $notifications = array('message' => 'Data Inserted Successfully', 'alert-type' => 'success');
        return redirect()->back()->with($notifications);

    }
}


// Table Workers
// big-int : id , string: name , big-int: nat_id , int: visit_id , int: visitor_id , timestamp: created_at , timestamp: updated_at