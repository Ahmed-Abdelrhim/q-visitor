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
        if (auth()->user()->employee->level == 0 ) {
            $date = Carbon::parse($request->input('logs_date'))->format('y-m-d');
            $visits = VisitingDetails::query()
                ->with('visitor:id,first_name,last_name')
                ->with('companions:id,first_name,last_name')
                ->where('checkin_at' , '>=', $date)
                ->get();

            return view('admin.logs.index' , ['visits' => $visits , 'show_data' => true]);
        }

        $notifications = array('message' => 'غير مسموح بعرض البيانات', 'alert-type' => 'info');
        return redirect()->back()->with($notifications);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}