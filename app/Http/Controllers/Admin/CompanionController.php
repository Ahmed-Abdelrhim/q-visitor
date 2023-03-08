<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companion;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CompanionController extends Controller
{
    public function index($id)
    {
    }

    public function addVisitCompanion($id)
    {
        $visit_id = $id;
        //        $visit = VisitingDetails::query()->with('visitor')
        //            ->where('visitor_id', $visit_id)
        //            ->orderBy('id', 'desc')
        //            ->first();
        $visit = VisitingDetails::query()->with('visitor')
            ->orderBy('id', 'desc')
            ->find($id);

        if (!$visit) {
            $notifications = array('message' => 'Something Went Wrong While Adding Companion', 'alert-type' => 'error');
            return redirect()->route('admin.OCR.index')->with($notifications);
        }
        return view('admin.ocr.layout_main', ['visit' => $visit, 'companion' => 'Y']);
    }

    public function addAnotherCompanion()
    {
        return $visit_id = $this->commonParts();
        return 'Done Another';
        return $visit_id;
    }

    public function addLastCompanion()
    {
        return $visit_id = $this->commonParts();
        return 'Done last';
        $notifications = array('message'=>'تم اضافة المرافقون بنجاح' ,'alert-type'=>'success');
        return redirect()->route('admin.OCR.index')->with($notifications);
    }


    public function commonParts()
    {
        $id = NULL;
        if (isset($_POST['id'])) {
            $id  = decrypt($_POST['id']);
        }

        // $visit = VisitingDetails::query()->where('visitor_id',$id)->first();
        $visit = VisitingDetails::query()->with('visitor')->find($id);
        if (!$visit) {
            $notification = array('error while finding visit details when adding another companion' ,'alert-type'=>'error');
            // return redirect()->route('admin.OCR.index')->with($notification);
            return 'Visit Not Found';
        }

        $name = null;
        $last_name = null;
        $notifications = array('message' => 'Success', 'alert-type' => 'success');
        if (isset($_POST['name'])) {
            $name = explode(" ", $_POST['name']);
            $last_name = substr(strstr($_POST['name'], " "), 1);
        }

        $gender = null;
        if (isset($_POST['gender'])) {
            $gender = $_POST['gender'];
            $gender = 5;
            if ($gender == 'F') {
                $gender = 10;
            }
        }


        $address = null;
        if (isset($_POST['address'])) {
            $address = $_POST['address'];
        }

        $nat_id = null;
        if (isset($_POST['nat_id'])) {
            $nat_id = $_POST['nat_id'];
        }


        $checkin_date = null;
        if (isset($_POST['checkin_date'])) {
            $checkin_date = $_POST['checkin_date'];
        }


        $checkin_time = null;
        if (isset($_POST['checkin_time'])) {
            $checkin_time = $_POST['checkin_time'];
        }

        if (isset($_POST['images'])) {
            $images = explode("||", $_POST['images']);
        }

        $perpic = null;
        if (isset($_POST['perpic'])) {
            $perpic = $_POST['perpic'];
        }

        $exdate = null;
        if (isset($_POST['exdate'])) {
            $exdate = $_POST['exdate'];
        }

        $plate_no = null;
        if (isset($_POST['plate_no'])) {
            $plate_no = $_POST['plate_no'];
        }


        try {
            DB::beginTransaction();
            $companion = Companion::query()->insert([
                'first_name' => $name[0],
                'last_name' => $last_name,
                'national_id' => $nat_id,
                'gender' => $gender,
                'visit_id' => $visit->id,
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Error While Adding Companion';
            // $notification = array('Error While Adding Companion' , 'alert-type'=>'error');
            // return redirect()->route('admin.OCR.index')->with($notification);
        }


        // Only Adding Images Of Companion
        return $visit->id;
    }
}
