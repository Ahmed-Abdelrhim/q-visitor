<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::query()->get();
        return view('admin.shipment.index',['shipments' => $shipments]);
    }

    public function create()
    {
        return view('admin.shipment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4',
        ]);

        if (!$request->has('no') && !$request->has('yes')) {
            $notification = array('message' => 'You Should Select The Qulaity Cnotrol For This Shipment', 'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $value = NULL;
        if ($request->has('yes')) {
            $value = 1;
        }

        if ($request->has('no')) {
            $value = 0;
        }

        Shipment::query()->create([
            'name' => $request->input('name'),
            'quality_check' => $value,
            'created_at' => Carbon::now(),
        ]);

//        try {
//            DB::beginTransaction();
//
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();
//            $notification = array('message' => __('files.Something Went Wrong While Adding The Shipment'), 'alert-type' =>'error');
//            return redirect()->back()->with($notification);
//        }
        return redirect()->route('admin.shipment.index');
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
