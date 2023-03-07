<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use DateTime;
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

        $date = Carbon::now()->format('Y-d-m');
        $visits = VisitingDetails::query()->with('visitor')
            ->whereRaw('date(checkin_at) = CURDATE() ')
            ->get();

        return view('admin.ocr.view', ['visits' => $visits]);
        // return view('admin.ocr.layout_main');
    }

    public function authorizedToView()
    {
        if (!Gate::allows('authorizedToViewOcr'))
            abort(403);
        return true;
    }

    public function viewScanPage($id)
    {
        $id = decrypt($id);
        $visit = VisitingDetails::query()->find($id);
        if (!$visit) {
            return 'This Visit Was Deleted';
        }
        return view('admin.ocr.layout_main', ['visit' => $visit]);
    }

    public function newScan()
    {
        return view('admin.ocr.new_scan');
    }

    public function searchVisitingDetails()
    {
        $date1 = Carbon::parse($_GET['v2date']);
        $date2 = Carbon::parse($_GET['v3date']);

        $visits = VisitingDetails::query()
            ->with('visitor')
            ->whereBetween('checkin_at', [$date1, $date2])
            ->get();
        if (!count($visits) > 0) {
            $notifications = array('message' => 'لم يتم ايجاد بيانات في هذا التاريخ', 'alert-type' => 'info');
            return redirect()->back()->with($notifications);
        }
        return view('admin.ocr.view', ['visits' => $visits]);
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
        $id = decrypt($id);
        VisitingDetails::query()->find($id)->delete();
        $notifications = array('message' => 'تم مسح الزيارة بنجاح', 'alert-type' => 'success');
        return redirect()->back()->with($notifications);
    }

    public function viewFirstPage()
    {
        $date = Carbon::now()->format('Y-d-m');
        $visits = VisitingDetails::query()->with('visitor')
            ->whereRaw('date(checkin_at) = CURDATE() ')
            ->get();
        return view('admin.ocr.view', ['visits' => $visits]);
        // $visits = VisitingDetails::query()->whereRaw('date(checkin_at) = CURDATE() ',[Carbon::now()->format('Y-m-d')])->get();
        // return $visits;
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
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $notifications = array('message' => 'Something Went Wrong', 'alert-type' => 'error');

            return redirect()->route('admin.OCR.index')->with($notifications);
        }
        $id = decrypt($id);

        $visit = VisitingDetails::query()->with('visitor')->find($id);
        if (!$visit) {
            $notifications = array('message' => 'لم يتم إيجاد هذة الزيارة', 'alert-type' => 'error');
            return redirect()->route('admin.OCR.index')->with($notifications);
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

        $add = null;
        if (isset($_POST['add'])) {
            $add = $_POST['add'];
        }

        $reg_no = $visit->reg_no;

        $data = $perpic;
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        if ($nat_id != '' && !empty($nat_id)) {
            $visit->visitor->national_identification_no = $nat_id;
            $visit->save();
        }

        if ($address != '' && !empty($address)) {
            $visit->visitor->address = $address;
            $visit->save();
        }

        if (!file_exists($reg_no)) {
            File::makeDirectory(storage_path('app/public' . '/per_images' . '/' . $reg_no), 0777, true, true);
        }

        try {
            $image = file_put_contents(storage_path('app/public' . '/' . 'per_images/' . $reg_no . '/' . $reg_no . '.png'), $data);
            $visit
                ->addMedia(storage_path('app/public' . '/' . 'per_images/' . $reg_no . '/' . $reg_no . '.png'))
                ->preservingOriginal()
                ->toMediaCollection('visitor');
        } catch (\Exception $e) {}

        if (!file_exists(storage_path('app/public' . '/images' . '/' . $reg_no))) {
            File::makeDirectory(storage_path('app/public' . '/images' . '/' . $reg_no), 0777, true, true);
        }

        try {
            foreach ($images as $counter => $img) {
                $img = str_replace("data:image/jpeg;base64,", "", $img);
                if ($img != '' or $img != ' ') {
                    file_put_contents(storage_path('app/public' . '/' . 'images/' . $reg_no . '/' . $nat_id . '-' . ($counter + 1) . '.jpg'), base64_decode($img));
                }
            }

            $create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $visit->visitor_id);
        } catch (\Exception $e) {}

        return $visit->visitor_id;
    }

    public function newScanSaveData()
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


        // $visitingDetail = VisitingDetails::query()->max('reg_no');
        $visitingDetail = VisitingDetails::query()->orderBy('id', 'desc')->first();
        $reg_no = $visitingDetail->reg_no + 1;


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
            $user = auth()->user();
            $employee = $user->employee;
            $emp_one = Null;
            $emp_two = Null;
            if ($employee->level == 1) {
                $emp_one = $employee->emp_one;
            }
            if ($employee->level == 2) {
                $emp_one = $employee->emp_one;
                $emp_one = $employee->emp_two;
            }

            try {
                DB::beginTransaction();
                $visiting_details = VisitingDetails::query()->create([
                    'reg_no' => $reg_no,
                    'purpose' => 'زيارة',
                    'company_name' => NULL,
                    'company_employee_id' => NULL,
                    'checkin_at' => Carbon::now(),
                    'checkout_at' => NULL,
                    'status' => 5,
                    'user_id' => $user->id,
                    'creator_employee' => $employee->id,
                    'emp_one' => $emp_one,
                    'emp_two' => $emp_two,
                    'employee_id' => $user->id,
                    'type_id' => 1,
                    'visitor_id' => $visitor->id,
                    'creator_type' => 'App\Scan',
                    'creator_id' => $user->id,
                    'editor_type' => 'App\User',
                    'editor_id' => $user->id,
                    'plate_no' => $plate_no,
                    'approval_status' => 0,
                    'created_at' => Carbon::now(),
                    'qrcode' => $qrcode,
                    'expiry_date' => NULL,
                ]);
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
                if (!file_exists(storage_path('app/public' . '/' .'per_images'))) {
                    $file = File::makeDirectory(storage_path('app/public' . '/' .'per_images'), 0777, true, true);
                }

                // File::makeDirectory(storage_path('app/public'.'/per_images'. '/' . $visiting_details->reg_no), 0777, true, true);


                File::makeDirectory(storage_path('app/public' . '/per_images' . '/' . $reg_no), 0777, true, true);



                $visitor_image = file_put_contents(storage_path('app/public' . '/' . 'per_images' . '/' . $reg_no . '/' . $reg_no . '.png'), $data);

                $visiting_details->addMedia(storage_path('app/public'.'/per_images'.'/'.$reg_no.'/'.$reg_no.'.png'))
                    ->preservingOriginal()
                    ->toMediaCollection('visitor');

                if (!file_exists(storage_path('app/public' . '/' .'images'))) {
                    $file = File::makeDirectory(storage_path('app/public' . '/' . 'images'), 0777, true, true);
                }

                File::makeDirectory(storage_path('app/public'.'/images'. '/' . $reg_no), 0777, true, true);

                foreach ($images as $counter => $img) {
                    $img = str_replace("data:image/jpeg;base64,", "", $img);
                    if ($img != '' or $img != ' ') {
                        file_put_contents(storage_path('app/public' . '/' . 'images' . '/' . $reg_no . '/' . $nat_id . '-' . ($counter + 1) . '.jpg'), base64_decode($img));
                    }
                }
                $create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $visitor->id);
            }
        } catch (\Exception $e) {
            $notifications = array('message' => 'add image not sent', 'alert-type' => 'info');
            return 'add image not sent';
        }
        return $visitor->id;
    }

    public function playy()
    {
        //        $qrcode = 'https://www.qudratech-eg.net/qrcode/temp/test8b6578560c26ddca8b2a6207e2fecb79.png';
        //        $visitors = Visitor::query()->take(20)->get();
        //
        //        for ($i = 0 ; $i < 10 ; $i++) {
        //            $random = $visitors->random();
        //            $id = $random->id;
        //            $reg_no = VisitingDetails::query()->orderBy('id','desc')->first()->reg_no;
        //            $visit = VisitingDetails::query()->create([
        //                'reg_no' => $reg_no + 1,
        //                'purpose' => 'Visit' . $i+1,
        //                'company_name' => 'Qudra-Tech',
        //                'checkin_at' => Carbon::now(),
        //                'checkout_at' => Carbon::now()->addHours(5),
        //                'status' => 5,
        //                'user_id' => 43,
        //                'creator_employee' => 42,
        //                'emp_one' => 41,
        //                'visitor_id' => $id,
        //                'employee_id' => 43,
        //                'creator_type' => 'App\User',
        //                'creator_id' => $id,
        //                // 'editor_type' => '',
        //                // 'editor_id' => '',
        //                'plate_no' => 'ل ق أ 284',
        //                'approval_status' => 0,
        //                'sent_sms_before' => 0,
        //                'qrcode' =>$qrcode,
        //                'created_at' => Carbon::now(),
        //            ]);
        //        }
        //        return 'Done';


        // ==========================================================

        //        $emp_one = NULL;
        //        $emp_two = NULL;
        //
        //        if (auth()->user()->employee->level == 1) {
        //            $emp_one = auth()->user()->employee->emp_one;
        //        }
        //
        //        if (auth()->user()->employee->level == 2) {
        //            $emp_one = auth()->user()->employee->emp_one;
        //            $emp_two = auth()->user()->employee->emp_two;
        //        }
        //        return 'Emp One => ' . $emp_one . '<br> Emp Two => ' . $emp_two;

        //        return auth()->user()->employee;
        //        $visit = VisitingDetails::query()->find(146);
        //        return $visit->creatorEmployee;
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
