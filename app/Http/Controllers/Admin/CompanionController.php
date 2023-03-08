<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;

class CompanionController extends Controller
{
    public function index($id)
    {
    }

    public function addVisitCompanion($id)
    {
        $visit_id = decrypt($id);
        $visit = VisitingDetails::query()->with('visitor')->find($visit_id);
        if (!$visit) {
            $notifications = array('message'=>'Something Went Wrong While Adding Companion','alert-type'=>'error');
            return redirect()->route('admin.OCR.index')->with($notifications);
        }


        return view('admin.ocr.layout_main',['visit'=>$visit , 'companion' => 'add']);
    }



}
