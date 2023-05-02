<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LogsController extends Controller
{
    public function index()
    {
        return view('admin.logs.index');
    }

    public function search(Request $request)
    {
        if (auth()->user()->employee->level == 0) {
            $date = Carbon::parse($request->input('logs_date'))->format('y-m-d');
            $start = Carbon::parse($date)->startOfDay();
            $end = Carbon::parse($date)->endOfDay();
            $visits = VisitingDetails::query()
                ->with('visitor:id,first_name,last_name')
                ->with('companions:id,first_name,last_name')
                ->with('shipment:id,name')
                // ->where('checkin_at', '>=', $date)
                ->whereBetween('checkin_at', [$start , $end])

                ->where(function ($query) {
                    $query->where('creator_employee', auth()->user()->employee->id)
                        ->orWhere('emp_one' , auth()->user()->employee->id)
                        ->orWhere('creator_id', auth()->user()->id)
                        ->orWhere('emp_two' , auth()->user()->employee->id);
                })
                ->get();

            return view('admin.logs.index', ['visits' => $visits, 'show_data' => true]);
        }

        $notifications = array('message' => 'غير مسموح بعرض البيانات', 'alert-type' => 'info');
        return redirect()->back()->with($notifications);
    }

    public function printLogs()
    {

    }
}
