<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use function PHPUnit\Framework\throwException;

class OcrController extends Controller
{
    public function index()
    {
        $this->authorizedToView();
        $this->linker();
        return view('admin.ocr.layout_main');
    }

    public function authorizedToView()
    {
        if (!Gate::allows('authorizedToViewOcr'))
            abort(403);
        return true;
    }


    public function create()
    {
        return view('admin.ocr.create');
    }

    public function store(Request $request)
    {
        // return $request;
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

        // $carPlateNumber = VisitingDetails::query()->latest()->first();
        $carPlateNumber = VisitingDetails::query()->orderBy('id', 'desc')->first();

        $myFile = fopen(storage_path('app/public/' . 'plate.txt'), "w");
        fwrite($myFile, $carPlateNumber->plate_no);
        return $carPlateNumber->plate_no;
    }

    public function ocrClear()
    {
        unlink(storage_path('app/public/' . 'plate.txt'));
        $fh = fopen(storage_path('app/public/' . 'plate.txt'), 'w');
        fclose($fh);
    }

    public function ocrIndexxar()
    {
        $this->authorizedToView();
        return view('admin.ocr.indexxar');
    }

    public function ocrPrint()
    {
        $id = $_GET['id'];
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
        $notifications = array('message' => 'Success', 'alert-type' => 'success');
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

        $data = $perpic;
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        try {
            DB::beginTransaction();
            $visitor = Visitor::query()->insert([
                'first_name' => $name[0],
                'last_name' => $last_name,
                'email' => 'visit@example.com',
                'phone' => '+15555555555',
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
            return throwException($e);
        }

        try {
            $qrcode = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . $name[0] . $reg_no);
            // $qrcode = file_get_contents('https://www.qudratech-eg.net/qrcode/index.php?data=' . $name[0] . $reg_no);
        } catch (\Exception $e) {
            $qrcode = NULL;
            $notifications = array('message' => 'visitor was not created , something went wrong', 'alert-type' => 'error');
        }

        $visitor = Visitor::query()->latest()->first();
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
                    'type_id' => 1,
                    'visitor_id' => $visitor->id,
                    'creator_type' => 'App\Scan',
                    'creator_id' => 1,
                    'editor_type' => 'App\Scan',
                    'editor_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'qrcode' => $qrcode,
                    'expiry_date' => NULL,
                    'plate_no' => $plate_no,
                ]);
                // TODO:: add this image to visiting details folder path
                // $visitingDetails->addMedia($image)->toMediaCollection('visitor');
                DB::commit();
            } catch
            (\Exception $e) {
                DB::rollBack();
                $notifications = array('message' => 'visit was not created , something went wrong', 'alert-type' => 'error');
                return redirect()->route('OCR.index')->with($notifications);
            }
        }

        try {
            if ($visiting_details) {
                if (!file_exists(storage_path('app/public' . '/per_images'))) {
                    $file = File::makeDirectory(storage_path('app/public' . '/per_images'), 0777, true, true);
                }


                file_put_contents(storage_path('app/public' . '/' . 'per_images/' . $reg_no . '.png'), $data);

                if (!file_exists(storage_path('app/public' . '/images'))) {
                    $file = File::makeDirectory(storage_path('app/public' . '/images'), 0777, true, true);
                }

                foreach ($images as $counter => $img) {
                    $img = str_replace("data:image/jpeg;base64,", "", $img);
                    if ($img != '' or $img != ' ') {
                        file_put_contents(storage_path('app/public' . '/' . 'images/' . $nat_id . '-' . ($counter + 1) . '.jpg'), base64_decode($img));
                    }
                }

                $create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $visitor->id);
            }
        } catch
        (\Exception $e) {
            $notifications = array('message' => 'add image not sent', 'alert-type' => 'info');
        }
        return $visitor->id;
    }

    public function playy()
    {
//        return auth()->user()->employee;
        $visit = VisitingDetails::query()->find(146);
        return $visit->creatorEmployee;
    }
}



//        $visit = VisitingDetails::query()->with('type')->find(279);
//        if ($visit->approval_status == 0) {
//            $status = 'Pending';
//            if (auth()->user()->hasRole(intval($visit->type->role_one))) {
//                $visit->approval_status = 1;
//                $visit->save();
//                return 'Will Show The First Approve Button';
//            } else {
//                return 'Will Not Show The First Approve Button';
//            }
//        }
//        if ($visit->approval_status == 1) {
//            $status = 'Waiting Fo Second Approval';
//            if(auth()->user()->hasRole(intval($visit->type->role_two)) ) {
//                $visit->approval_status = 2;
//                $visit->save();
//                return 'Will Show The Second Approve Button';
//            } else {
//                return 'Will Not Show The Second Approve Button';
//            }
//        }
//
//        if ($visit->approval_status == 2) {
//            $status = 'Approved';
//            return $status;
//        }

//        return auth()->user()->employee;
//        // return $last = VisitingDetails::query()->latest()->first();
//        if (!file_exists(storage_path('app/public' . '/playing'))) {
//            $file = File::makeDirectory(storage_path('app/public' . '/' . 'playing'), 0777, true, true);
//            return 'File Created Successfully';
//        }
//        return 'File Already Exists';
