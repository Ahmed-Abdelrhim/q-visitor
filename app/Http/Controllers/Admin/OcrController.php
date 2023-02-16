<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\throwException;

class OcrController extends Controller
{
    public $new_request;

    public function index()
    {
        $this->linker();

//        if (filesize( storage_path('app/public/'.'plate.txt') > 0 )) {
//        $plate = File::get(filesize(storage_path('app/public/'.'plate.txt')));
//        }
//        else {
//            $plate = '';
//        }


        return view('admin.ocr.layout_main');
    }


    public function create()
    {
        return view('admin.ocr.create');
    }

    public function store(Request $request)
    {
        return $request;
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

    public function linker()
    {
        exec("tasklist 2>NUL", $task_list);
        $serv1 = false;
        $serv2 = false;
        foreach ($task_list as $a) {
            $str = explode(" ", $a);
            if ($str[0] == "SinosecuRecog.exe") {
                $serv1 = true;
            }
            if ($str[0] == "SinosecuCtl.exe") {
                $serv2 = true;
            }
        }

        if (!$serv1) {
            exec("SinosecuRecog.exe");
        }
        if (!$serv2) {
            exec("SinosecuCtl.exe");
        }

    }

    public function getLastCarPlate()
    {
        //        $qry22 = "SELECT plate_no FROM `visiting_details` order by id DESC limit 1";
        //        $result22 = $conn->query($qry22);
        //
        //        while ($row = $result22->fetch_assoc()) {
        //            $plate_no = $row["plate_no"];
        //        }

        $carPlateNumber = VisitingDetails::query()->latest()->first();

        $myFile = fopen(storage_path('app/public/' . 'plate.txt'), "w");
        fwrite($myFile, $carPlateNumber->reg_no);
        return $carPlateNumber->reg_no;
    }

    public function ocrClear()
    {
        // File::get(filesize(storage_path('app/public/'.'plate.txt')))
        unlink(storage_path('app/public/' . 'plate.txt'));
        $fh = fopen(storage_path('app/public/' . 'plate.txt'), 'w');
        fclose($fh);
    }

    public function ocrIndexxar()
    {
        return view('admin.ocr.indexxar');
    }

    public function ocrPrint($id = null)
    {
        $data = VisitingDetails::query()
            ->with('visitor')
            ->where('visitor_id', $id)
            ->first();
        return view('admin.ocr.print', ['data' => $data]);
    }

    public function ocrSave()
    {
        $name = null;
        $last_name = null;
        if (isset($_POST['name'])) {
            $name = explode(" ", $_POST['name']);
            $last_name = substr(strstr($_POST['name'], " "), 1);
            // $name = $_POST['name'];
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
            // TODO:: save images
            //            foreach ($images as $counter => $img) {
            //                $img = str_replace("data:image/jpeg;base64,", "", $img);
            //                if ($img != '' or $img != ' ') {
            //                    // file_put_contents('images/' . $nat_id . '-' . $counter . '.jpg', base64_decode($img));
            //                    // $counter++;
            //                }
            //            }
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

        $add = null;
        if (isset($_POST['add'])) {
            $add = $_POST['add'];
        }

        $visitingDetail = VisitingDetails::query()->max('reg_no');
        $reg_no = $visitingDetail + 1;

        // TODO : works till here
        // return response()->json(['data' => 'done']);

        $data = $perpic;
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        $this->new_request = $data;
        // TODO:: upload per images here
        // file_put_contents(storage_path('per_images/') . $reg_no . '.png', $data);
        // Storage::disk('public')->putFileAs('per_images/' . $reg_no . '.png', '' , '');

        file_put_contents(storage_path('app/public' . '/' . 'per_images/' . $reg_no . '.png'), $data);
        //        try {
        //
        //            // Storage::putFileAs( 'per_images',new File($data) , $reg_no . '.png');
        //        } catch (\Exception $e) {
        //            return response()->json(['data' => $e]);
        //        }

        try {
            DB::beginTransaction();
            $visitor = Visitor::query()->insert([
                'first_name' => $name[0],
                'last_name' => $last_name,
                'email' => null,
                'phone' => null,
                'gender' => $gender,
                'address' => $address,
                'national_identification_no' => $nat_id,
                'is_pre_register' => 1,
                'status' => 5,
                'creator_type' => 'App\Scan',
                'creator_id' => 1,
                'editor_type' => 'App\Scan',
                'editor_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => null,
                'photo' => $perpic,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $notifications = array('message' => 'Qrcode was not sent', 'alert-type' => 'info');
            // return redirect()->route('OCR.index')->with($notifications);
            return throwException($e);
        }

        try {
            $qrcode = file_get_contents('https://www.qudratech-eg.net/qrcode/index.php?data=' . $name[0] . $reg_no);
        } catch (\Exception $e) {
            $notifications = array('message' => 'visitor was not created , something went wrong', 'alert-type' => 'error');
        }

        if ($visitor) {
            try {
                DB::beginTransaction();
                $visiting_details = VisitingDetails::query()->insert([
                    'reg_no' => $reg_no,
                    'purpose' => 'زيارة',
                    'company_name' => NULL,
                    'company_employee_id' => NULL,
                    'checkin_at' => Carbon::now(),
                    'checkout_at' => NULL,
                    'status' => 5,
                    'user_id' => 3,
                    'employee_id' => 3,
                    'visitor_id' => $visitor->id,
                    'creator_type' => 'App\Scan',
                    'creator_id' => 1,
                    'editor_type' => 'App\Scan',
                    'editor_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'qrcode' => $qrcode,
                    'expiry_date' => $exdate,
                    'plate_no' => $plate_no,
                ]);

                if ($visiting_details) {
                    foreach ($images as $counter => $img) {
                        $img = str_replace("data:image/jpeg;base64,", "", $img);
                        if ($img != '' or $img != ' ') {
                            // file_put_contents('images/' . $nat_id . '-' . $counter . '.jpg', base64_decode($img));
                            $visiting_details->addMedia($img)->toMediaCollection('visitor');
                            // $counter++;
                        }
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $notifications = array('message' => 'visit was not created , something went wrong', 'alert-type' => 'error');
                return redirect()->route('OCR.index')->with($notifications);
            }
        }

        try {
            $create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $visitor->id);
        } catch (\Exception $e) {
            $notifications = array('message' => 'add image not sent', 'alert-type' => 'info');
        }


        return response()->json(['status' => 200, 'message' => 'Done Successfully']);

        // VD = 187
        // visitors = 218
    }

    public function playy()
    {
        return $this->new_request;
        return $visitingDetail = VisitingDetails::query()->max('reg_no');
    }
}



//        $images = explode("||", $_GET['images']);
//        if (isset($_GET['images'])) {
//            $images = explode("||", $_GET['images']);
//        }


// $name = explode(" ", $_GET['name']);
//        $lastname = substr(strstr($_GET['name'], " "), 1);
//        if (isset($_GET['gender'])) {
//            $gender = $_GET['gender'];
//        }
//        $address = $_GET['address'];
//        $nat_id = $_GET['nat_id'];
//        $perpic = $_GET['perpic'];
//        $exdate = $_GET['exdate'];
//        $plate_no = $_GET['plate_no'];
//        $add = $_GET['add'];
//        $images = explode("||", $_GET['images']);
//        $max = 0;
//        $reg_no = 0;
//        $counter = 1;


// return response()->json(['data' => 'Not A Ajax Request']);

//        if ($request->gender == 'M') $gender = '5'; else $gender = '10';
//
//        if ($request->add == 'N') {
//            unlink(storage_path('app/public/' . 'plate.txt'));
//            $fh = fopen(storage_path('app/public/' . 'plate.txt'), 'w');
//            fclose($fh);
//        } else {
//            $myFile = fopen(storage_path('app/public/' . 'plate.txt'), "w");
//            fwrite($myFile, $request->plate_no);
//        }


// return response()->json(['data' =>$request]);


