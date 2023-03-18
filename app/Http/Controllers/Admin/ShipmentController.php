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

        try {
            DB::beginTransaction();
            Shipment::query()->create([
                'name' => $request->input('name'),
                'quality_check' => $value,
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $notification = array('message' => __('files.Something Went Wrong While Adding The Shipment'), 'alert-type' =>'error');
            return redirect()->back()->with($notification);
        }
        $notification = array('message' => __('files.Shipment Created Successfully'), 'alert-type' =>'success');

        return redirect(route('admin.shipment.index'))->with($notification);
    }


    public function show($id)
    {

    }

    public function edit($id)
    {
        $shipment = Shipment::query()->find($id);
        if (!$shipment) {
            $notifications = array('message' =>'Shipment Was Not Found','alert-type'=>'error');
            return redirect()->back()->with($notifications);
        }

        return view('admin.shipment.edit',['shipment' => $shipment]);
    }


    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $shipment = Shipment::query()->find($id);
        if (!$shipment) {
            $notifications = array('message' =>'Shipment Was Not Found To be Updated','alert-type'=>'error');
            return redirect()->back()->with($notifications);
        }
        $request->validate([
            'name' => 'required|string|min:4'
        ]);

        if (!$request->has('no') && !$request->has('yes')) {
            $notification = array('message' => __('files.You Should Select The Qulaity Cnotrol For This Shipment'), 'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $value = NULL;
        if ($request->has('yes')) {
            $value = 1;
        }

        if ($request->has('no')) {
            $value = 0;
        }

        $shipment->update([
            'name' => $request->input('name'),
            'quality_check' => $value,
            'updated_at' => Carbon::now(),
        ]);
        $notification = array('message' => __('files.Shipment Updated Successfully'), 'alert-type'=>'success');
        return redirect(route('admin.shipment.index'))->with($notification);
    }


    public function destroy($id)
    {
        $id = decrypt($id);
        $shipment = Shipment::query()->find($id);
        if (!$shipment) {
            $notification = array('messgae' => __('files.Shipment Was Not Found') , 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $shipment->delete();
        $notification = array('messgae' => __('files.Shipment Was deleted successfully') , 'alert-type' => 'success');
        return redirect(route('admin.shipment.index'))->with($notification);
    }
}
