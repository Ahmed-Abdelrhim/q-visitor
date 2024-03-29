<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companion;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        // return 'Done Another';
        // return $visit_id;
    }

    public function addLastCompanion()
    {
        return $visit_id = $this->commonParts();
        // return 'Done last';
        // $notifications = array('message'=>'تم اضافة المرافقون بنجاح' ,'alert-type'=>'success');
        // return redirect()->route('admin.OCR.index')->with($notifications);
    }


    public function commonParts()
    {
        $id = NULL;
        if (isset($_POST['id'])) {
            $id = decrypt($_POST['id']);
        }

        // $visit = VisitingDetails::query()->where('visitor_id',$id)->first();
        $visit = VisitingDetails::query()->with('visitor')->find($id);
        if (!$visit) {
            $notification = array('error while finding visit details when adding another companion', 'alert-type' => 'error');
            // return redirect()->route('admin.OCR.index')->with($notification);
            return 'Visit Not Found';
        }


        $name = NULL;
        $last_name = NULL;
        $notifications = array('message' => 'Success', 'alert-type' => 'success');
        if (isset($_POST['name'])) {
            $name = explode(" ", $_POST['name']);
            $last_name = substr(strstr($_POST['name'], " "), 1);
        }

        $gender = NULL;
        if (isset($_POST['gender'])) {
            $gender = $_POST['gender'];
            $gender = 5;
            if ($gender == 'F') {
                $gender = 10;
            }
        }

        $address = NULL;
        if (isset($_POST['address'])) {
            $address = $_POST['address'];
        }

        $nat_id = NULL;
        if (isset($_POST['nat_id'])) {
            $nat_id = $_POST['nat_id'];

            if (!is_integer($nat_id))
                $nat_id = NULL;
        }


        $checkin_date = NULL;
        if (isset($_POST['checkin_date'])) {
            $checkin_date = $_POST['checkin_date'];
        }


        $checkin_time = NULL;
        if (isset($_POST['checkin_time'])) {
            $checkin_time = $_POST['checkin_time'];
        }

        if (isset($_POST['images'])) {
            $images = explode("||", $_POST['images']);
        }

        $perpic = NULL;
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


        $data = $perpic;
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        // return 'First Name => ' . $name[0] . ' Last Name => ' . $last_name . ' National ID => ' . $nat_id . ' Gender => '. $gender . ' Visit_id => ' . $visit->id;


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
        }
        // Only Adding Images Of Companion

        if (!file_exists(storage_path('app/public' . '/per_images' . '/' . $visit->reg_no . '/companions'))) {
            File::makeDirectory(storage_path('app/public' . '/per_images' . '/' . $visit->reg_no . '/companions'), 0777, true, true);
        }
        if ($companion) {
            $companion = Companion::query()->orderBy('id', 'desc')->first();
        }

        // adding only face image
        try {
            $image = file_put_contents(storage_path('app/public' . '/' . 'per_images' . '/' . $visit->reg_no . '/' . 'companions' . '/' . $companion->id . '.png'), $data);
            $companion
                ->addMedia(storage_path('app/public' . '/' . 'per_images/' . $visit->reg_no . '/companions/' . $companion->id . '.png'))
                ->preservingOriginal()
                ->toMediaCollection('companion');
        } catch (\Exception $e) {
        }


        // adding all identification images

        try {
            if (!file_exists(storage_path('app/public' . '/images/' . $visit->reg_no . '/companions'))) {
                File::makeDirectory(storage_path('app/public' . '/images/' . $visit->reg_no . '/companions'), 0777, true, true);
            }

            foreach ($images as $counter => $img) {
                $img = str_replace("data:image/jpeg;base64,", "", $img);
                if ($img != '' or $img != ' ') {
                    file_put_contents(storage_path('app/public' . '/images/' . $visit->reg_no . '/companions/' . $visit->reg_no . '-' . $companion->id . '-' .  ($counter + 1) . '.jpg'), base64_decode($img));
                }
            }
        } catch (\Exception $e) {}


        return (int) $visit->id;
    }


    public function removeCompanion($companion_id)
    {
        $id = decrypt($companion_id);
        $companion = Companion::query()->with('visit')->find($id);
        if (!$companion) {
            $notifications = array('message'=> __('files.Companion Was Not Found'), 'alert-type' => 'error');
            return redirect()->back()->with($notifications);
        }

        // Delete Companion Images Int The Per_Images Folder
        try {
            $reg_no = $companion->visit->reg_no;
            if (File::exists(storage_path('app/public' . '/per_images' . '/' . $reg_no .'/companions' ))) {

                $image_path = storage_path('app/public' .'/per_images' . '/' . $reg_no . '/companions/' . $companion->id . '.png');
                File::delete($image_path);
            }
        } catch (\Exception $e) {}



        // Delete Companion Images In The Images Folder
        try {
            if (File::exists(storage_path('app/public' . '/images' . '/' . $reg_no .'/companions' ))) {
                for ($i = 1 ; $i <= 5 ; $i ++) {
                    $image_path = storage_path('app/public' .'/images' . '/' . $reg_no . '/companions/' . $reg_no . '-' . $companion->id . '-' . $i . '.jpg');
                    File::delete($image_path);
                }
            }

        } catch (\Exception $e) {}


        $companion->delete();
        $notifications = array('message'=> __('files.Companion Deleted Successfully') , 'alert-type' => 'success');
        return redirect()->back()->with($notifications);
    }
}
