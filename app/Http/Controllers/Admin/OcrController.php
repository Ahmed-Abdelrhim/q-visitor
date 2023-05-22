<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Services\Employee\EmployeeService;
use App\Jobs\BackgroundJob;
use App\Jobs\NotifyEmployee;
use App\Mail\VisitorMail;
use App\Models\CarPlate;
use App\Models\Companion;
use App\Models\Department;
use App\Models\Employee;
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
use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\IFTTTHandler;
use Spatie\Permission\Models\Role;
use App\User;

use function PHPUnit\Framework\throwException;
use App\Jobs\SqlServerJob;
use Twilio\Rest\Client;


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class OcrController extends Controller
{
    public function index()
    {
        $this->authorizedToView();
        $this->linker();

        $date = Carbon::now()->format('Y-d-m');
        $visits = VisitingDetails::query()
            ->with('visitor')
            ->with('employee')
            ->whereRaw('date(checkin_at) = CURDATE() ')
            ->orderBy('id', 'desc')
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

    public function viewScanPage($id = null)
    {
        $id = decrypt($id);
        $visit = VisitingDetails::query()->find($id);
        if (!$visit) {
            return 'This Visit Was Deleted';
        }


        if ($visit->car_type == 'P') {
            $person_visit = 'Yes';
            return view('admin.ocr.layout_main', ['visit' => $visit, 'person_visit' => $person_visit]);
        }
        // Here Get The Cars Plate Numbers
        return view('admin.ocr.layout_main', ['visit' => $visit]);
    }

    public function searchWithCarPlateNumber(Request $request)
    {
        $searchTerm = $request->value;
        $data = CarPlate::query()->where('plate_number', 'like', "%{$searchTerm}%")
            ->get(); // Customize the columns you want to retrieve
        return response()->json($data);
    }

    public function newScan($car_type)
    {
        $car_type = decrypt($car_type);
        $car_type_array = ['T', 'C', 'P', 'TWIN_TRUCK'];
        if (!in_array($car_type, $car_type_array)) {
            $notifications = array('message' => 'Invalid car type', 'alert-type' => 'error');
            return redirect()->back()->with($notifications);
        }

        $twin_truck = 'No';
        $employees = Employee::query()->where('status', Status::ACTIVE)->get(['id', 'first_name', 'last_name']);
        if ($car_type == 'TWIN_TRUCK') {
            $twin_truck = 'YES';
            // return view('admin.ocr.new_scan', ['car_type' => $car_type, 'employees' => $employees, 'twin_truck' => $twin_truck]);
        }

        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();



        // Get Car Plates Today
        $car_plates = CarPlate::query()
            ->whereBetween('created_at', [$start, $end])
            ->where('flag', false)
            ->get();

        // return $car_plates;
        return view('admin.ocr.new_scan', ['car_type' => $car_type, 'employees' => $employees, 'car_plates' => $car_plates, 'twin_truck' => $twin_truck]);
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
        //        $data = VisitingDetails::query()
        //            ->with('visitor')
        //            ->where('visitor_id', $id)
        //            ->first();

        $data = VisitingDetails::query()
            ->with('visitor')
            ->find($id);
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

        // if (!file_exists($reg_no)) {
        if (!file_exists(storage_path('app/public' . '/per_images' . '/' . $reg_no))) {
            File::makeDirectory(storage_path('app/public' . '/per_images' . '/' . $reg_no), 0777, true, true);
        }

        try {
            $image = file_put_contents(storage_path('app/public' . '/' . 'per_images/' . $reg_no . '/' . $reg_no . '.png'), $data);
            $visit
                ->addMedia(storage_path('app/public' . '/' . 'per_images/' . $reg_no . '/' . $reg_no . '.png'))
                ->preservingOriginal()
                ->toMediaCollection('visitor');
        } catch (\Exception $e) {
        }

        if (!file_exists(storage_path('app/public' . '/images' . '/' . $reg_no))) {
            File::makeDirectory(storage_path('app/public' . '/images' . '/' . $reg_no), 0777, true, true);
        }

        try {
            foreach ($images as $counter => $img) {
                $img = str_replace("data:image/jpeg;base64,", "", $img);
                if ($img != '' or $img != ' ') {
                    file_put_contents(storage_path('app/public' . '/' . 'images/' . $reg_no . '/' . $reg_no . '-' . ($counter + 1) . '.jpg'), base64_decode($img));
                }
            }

            $create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $visit->visitor_id);
        } catch (\Exception $e) {
        }


        // notify the employee here···
        //        try {
        //            $JOB = NotifyEmployee::dispatch($visit);
        //        } catch (\Exception $e) {}


        return $visit->id;
    }

    public function addCompanionToVisit($id)
    {

    }

    public function newScanSaveData()
    {
        $emp_id = NULL;
        // $emp_id = auth()->user()->id;
        if (isset($_POST['employee_id'])) {
            $emp_id = $_POST['employee_id'];
            if ($emp_id == 0 || empty($emp_id) || !is_numeric($emp_id)) {
                $emp_id = auth()->user()->employee->id;
                // $emp_id = auth()->user()->id;
                // return 'Employee Is Not Specified';
            }
        }

        $plate_number = null;
        if (isset($_POST['car_plate_number'])) {
            $plate_number = $_POST['car_plate_number'];
        }

        if (empty($plate_number)) {
            return 'Car Plate Is Not Specified';
        }

        //        if (empty($emp_id) || $emp_id == 0) {
        //            return 'Employee Is Not Specified';
        //        }


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


        $car_type = NULL;
        if (isset($_POST['car_type'])) {
            $car_type = $_POST['car_type'];
        }

        //        if ($car_type == 'TWIN_TRUCK') {
        //            if (!isset($_POST['twin_truck_number'])) {
        //                return 'Twin Truck Error';
        //            }
        //        }

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
                // 'email' => 'visit@example.com',
                // 'phone' => '+15555555555',
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
            return 'Visitor Error';
            // $notifications = array('message' => 'Qrcode was not sent', 'alert-type' => 'info');
            // $notifications = array('message' => 'Visitor Was Not Created', 'alert-type' => 'error');
            // return throwException($e);
        }

        //        try {
        //            $qrcode = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . $name[0] . $reg_no);
        //            // $qrcode = file_get_contents('https://www.qudratech-eg.net/qrcode/index.php?data=' . $name[0] . $reg_no);
        //        } catch (\Exception $e) {
        //            $qrcode = NULL;
        //            $notifications = array('message' => 'visitor was not created , something went wrong', 'alert-type' => 'error');
        //        }


        if ($visitor) {
            $visitor = Visitor::query()->orderBy('id', 'desc')->first();
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
                    'user_id' => $emp_id,
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
                    'plate_no' => $plate_number,
                    'car_type' => $car_type,
                    'approval_status' => 0,
                    'created_at' => Carbon::now(),
                    'qrcode' => Null,
                    'expiry_date' => Carbon::now()->endOfDay(),
                ]);
                DB::commit();
            } catch
            (\Exception $e) {
                DB::rollBack();
                return 'Visit Error';
            }
        }

        try {
            $car = CarPlate::query()->where('plate_number', $plate_number)->first();
            $car->flag = 1;
            $car->save();
        } catch (\Exception) {
        }

        try {
            $visit = VisitingDetails::query()->orderBy('id', 'desc')->first();
            $qrcode = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . $visit->id);
            $visit->qrcode = $qrcode;
            $visit->save();
        } catch (\Exception $e) {
            $qrcode = NULL;
            $notifications = array('message' => 'visitor was not created , something went wrong', 'alert-type' => 'error');
        }


        try {
            $visit = VisitingDetails::query()->with('visitor')->orderBy('id', 'desc')->first();
            DB::connection('sqlsrv')
                ->statement("INSERT INTO visits  (visit_id, visitor_name , date_from , date_to , flag)
                                        VALUES ( " . $visit->id . " , N' " . $visit->visitor->name . " ' , '" . $visit->checkin_at . "' , '" . $visit->expiry_date . "' , 1 );");

        } catch (\Exception $e) {
            // return 'SQL Server Connection Error';
        }


        try {
            if ($visiting_details) {
                if (!file_exists(storage_path('app/public' . '/' . 'per_images'))) {
                    $file = File::makeDirectory(storage_path('app/public' . '/' . 'per_images'), 0777, true, true);
                }
                File::makeDirectory(storage_path('app/public' . '/per_images' . '/' . $reg_no), 0777, true, true);
                $visitor_image = file_put_contents(storage_path('app/public' . '/' . 'per_images' . '/' . $reg_no . '/' . $reg_no . '.png'), $data);
                $visiting_details->addMedia(storage_path('app/public' . '/per_images' . '/' . $reg_no . '/' . $reg_no . '.png'))
                    ->preservingOriginal()
                    ->toMediaCollection('visitor');

                if (!file_exists(storage_path('app/public' . '/' . 'images'))) {
                    $file = File::makeDirectory(storage_path('app/public' . '/' . 'images'), 0777, true, true);
                }
                File::makeDirectory(storage_path('app/public' . '/images' . '/' . $reg_no), 0777, true, true);

                for ($i = 0; $i <= 7; $i++) {
                    if ($i == 0 || $i == 1 || $i == 5) {
                        $img = str_replace("data:image/jpeg;base64,", "", $images[$i]);
                        if ($img != '' or $img != ' ') {
                            file_put_contents(storage_path('app/public' . '/' . 'images' . '/' . $reg_no . '/' . $nat_id . '-' . ($i + 1) . '.jpg'), base64_decode($img));
                        }
                    } else {
                        continue;
                    }
                }

                //                foreach ($images as $counter => $img) {
                //                    $img = str_replace("data:image/jpeg;base64,", "", $img);
                //                    if ($img != '' or $img != ' ') {
                //                        file_put_contents(storage_path('app/public' . '/' . 'images' . '/' . $reg_no . '/' . $nat_id . '-' . ($counter + 1) . '.jpg'), base64_decode($img));
                //                    }
                //                }
                // $create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $visitor->id);
            }
        } catch (\Exception $e) {
            $notifications = array('message' => 'add image not sent', 'alert-type' => 'info');
            return 'add image not sent';
        }
        return $visiting_details->id;
    }

    public function xmlData()
    {
        $xmlString = file_get_contents(storage_path('app/public' . '/xml/sample.xml'));
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);
        return $data = json_decode($json, true);
        // return $data['food'][0]['name'];
    }

    public function playy()
    {
        return $data = Http::get('http://192.168.2.125:8081/api/Log/ReadAllLogs');

        $name = 'name';
        // strpos()
        return $visits = VisitingDetails::query()->with('owner')
            ->join('employees', 'visiting_details.user_id', '=', 'employees.id')
            //->join('users', 'owners.emp_one', '=', 'users.id')
            //->where('employees.emp_one', '=', auth()->user()->employee->id)
            ->find(451);


        return $visit = VisitingDetails::query()
            ->with(['owner' => function ($query) {
                $query->where('emp_one', '=', auth()->user()->employee->id);
            }])->find(451);


        return $visit = VisitingDetails::query()->with('owner')->find(449);
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();

        $car_plates = CarPlate::query()
            ->whereBetween('created_at', [$start, $end])
            ->where('flag', false)
            ->get();

        return $car_plates;

        $user_id = 53;
        $employee = Employee::query()->find($user_id);
        return $employee->level;
        $strict_types = 1;

        require_once('./../vendor/autoload.php');

        $options = new QROptions(
            [
                'eccLevel' => QRCode::ECC_L,
                'imageBase64' => false,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version' => 5,
            ]
        );


        $qrcode = (new QRCode($options))->render(37);
        return $qrcode;
        // $qrcode = str_replace("data:image/svg+xml;base64,", "", $qrcode);


        if (!file_exists(storage_path('app/public' . '/qrcode/37'))) {
            $file = File::makeDirectory(storage_path('app/public' . '/' . 'qrcode'), 0777, true, true);
        }


        $filePath = Storage::disk('public')->putFileAs('qrcode/37', $qrcode, 'qrcode.png');
        return Storage::url($filePath);

        // return base64_decode('d52e7e19db409a857b5c21cb6e0b47ae');
        // declare(strict_types=1);


        return session()->get('password_hash_web');
        return session()->all();
        return session()->get('session');
        // session()->flush();
        // return 'Done';
        $data = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . 2);
        return $data;
        return $visits = DB::connection('sqlsrv')->table('visits')->get();

        return Carbon::now()->endOfDay();
        $http = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . 1);
        return $http;

        return $sms = file_get_contents("https://www.qudratech-sd.com/sms_api.php?mob=" . '01152067271');

        //        $twilio_sid = 'ACdd4597bb33934ae1d78536fb528d3c80';
        //        $twilio_token = 'f04d3ff44c05dfc02c52c43ff5e64b04';
        //        $twilio_from = '+15005550006';
        //
        //        $client = new Client('ACdd4597bb33934ae1d78536fb528d3c80', 'f04d3ff44c05dfc02c52c43ff5e64b04');
        //
        //        $client->messages->create('+20 1152067271', [
        //            'from' => $twilio_from,
        //            'body' => 'Body']);
        //
        //        return 'Done';

        //        $visit = VisitingDetails::query()->with('visitor')->find(338);
        //
        //
        //                $send_email = BackgroundJob::dispatch($visit);
        //
        //                return 'Done';
        //
        //        return view('admin.email.visitor_mail',[
        //                'visitor_name' =>$visit->visitor->name ,
        //                'visit_date' => $visit->checkin_at ,
        //                'qr_code' => $visit->qrcode]);

        //        $visit = VisitingDetails::query()->with('visitor')->orderBy('id', 'desc')->first();
        //
        //            DB::connection('sqlsrv')
        //                ->statement("INSERT INTO visits  (visit_id, visitor_name , date_from , date_to , flag)
        //                                        VALUES ( " . $visit->id . " , N' " . $visit->visitor->name . " ' , '" . $visit->checkin_at . "' , '" . $visit->expiry_date . "' , 1 );");
        //
        //

        return $visits = DB::connection('sqlsrv')->table('visits')->get();
        //
        //        foreach ($visits as $visit) {
        //            DB::connection('sqlsrv')->table('visits')->where('vid', $visit->vid)->delete();
        //        }
        //        return 'Done';


        $main = Http::get('https://api.hik-proconnect.com/');

        // Token Get
        //        $response = Http::post('https://api.hik-proconnect.com/api/hpcgw/v1/token/get' , [
        //            'appKey' => '1945zr2ue3ncrg9ypwimwtv27v061uba',
        //            'secretKey' => 'prqezqcgnpgdrj0gldiw95vtcetj9b9t'
        //        ]);
        //
        //        return $response->json();


        // Site Add
        //        $add = Http::withHeaders([
        //            'Content-Type' => 'application/json',
        //            'Authorization' => 'Bearer hpc.xksnvqy1rg2crxa77421b7kxeh179ckj'
        //        ])->post('https://api.hik-proconnect.com/api/hpcgw/v1/site/add', [
        //            'name' => 'QudraTech',
        //            'siteState' => 'Cairo',
        //            'siteCity' => 'Cairo',
        //            'timeZone' => '147',
        //            'location' => 'Egypt , Cairo , Nasr City'
        //        ]);
        //
        //        return $add->json();


        //        Site Search
        //                $site_search = Http::withHeaders([
        //                    'Content-Type' => 'application/json',
        //                    'Authorization' => 'Bearer hpc.xksnvqy1rg2crxa77421b7kxeh179ckj'
        //                ])->post('https://api.hik-proconnect.com/api/hpcgw/v1/site/search', [
        //                    'page' => 0,
        //                    'pageSie' => 0,
        //                    'search' => 'QudraTech'
        //                ]);
        //
        //                // id = 8a7488ec87bb9a010187bd2f7fee0743
        //                return $site_search->json();


        // Device Add
        $device_add = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer hpc.xksnvqy1rg2crxa77421b7kxeh179ckj'
        ])->post('https://api.hik-proconnect.com/api/hpcgw/v2/device/add', [
            'deviceList' => [
                'deviceSerial' => 'j21312692',
                'validateCode' => 'Camera01',
            ],
            'siteId' => '8a7488ec87bb9a010187bd2f7fee0743',
        ]);

        return $device_add->json();


        //        $date_from = Carbon::now();
        //        $date_to = Carbon::now()->addHours(3);
        //
        //        return $visits = DB::connection('sqlsrv')->table('visits')->get();


        // $sql = SqlServerJob::dispatch(400, $name, $date_from, $date_to);


        //        DB::connection('sqlsrv')
        //            ->statement("INSERT INTO visits  ( visit_id , visitor_name , date_from , date_to )
        //                                    VALUES ( ". 400 . " ,'" . $name . "'  , '". $date_from  ."' , '". $date_to . "' ); ");


        // DB::connection('sqlsrv')->statement("INSERT INTO visits  (visit_id, visitor_name) VALUES ( ". $visit->id . " ,'" . $visit->visitor->name . "' ); ");

        //        DB::connection('sqlsrv')
        //            ->statement("INSERT INTO visits  (visit_id, visitor_name , date_from , date_to ) VALUES ( 400 , 'Ahmed ' , '". $date_from ."' , '".$date_to ."' ) " );


        // ->orderBy('visit_id', 'asc')->get();

        //                $visits = DB::connection('sqlsrv')->table('visits')->orderBy('visit_id', 'asc')->get();
        //
        //                foreach ($visits as $visit) {
        //                    DB::connection('sqlsrv')->statement(" DELETE FROM visits WHERE visit_id= " . $visit->visit_id . "; ");
        //                }
        //
        //                return 'Done';


        // DB::connection('sqlsrv')->statement("INSERT INTO visits  (visit_id, visitor_name) VALUES (355 , ' Ahmed' ); ");

        // DB::connection('sqlsrv')->insert();

        //        if (File::exists(storage_path('app/public' . '/images' . '/' . '26102276' .'/companions' ))) {
        //            for ($i = 1 ; $i <= 5 ; $i ++) {
        //                $image_path = storage_path('app/public' .'/images' . '/' . '26102276' . '/companions/' . '26102276'. '-' . 19 . '-' . $i . '.jpg');
        //                File::delete($image_path);
        //            }
        //        }
        //
        //            return 'Done';
        //
        //
        //
        //
        //        return auth()->user()->employee->level;
        //        return auth()->user()->creatorEmployee;
        //        $visit_id_for_qr_code = VisitingDetails::query()->orderBy('id','desc')->first()->id;
        //        $url = 'https://www.qudratech-eg.net/qrcode/index.php?data=' . $visit_id_for_qr_code;
        //
        //
        //        return $data = file_get_contents($url);


        //        $lang = Languages::query()->create([
        //            'iso' => 'es',
        //            'active' => 1,
        //            'created_at' => Carbon::now(),
        //        ]);
        //
        //        return 'Done';

        $visit = VisitingDetails::query()->find(355);

        if ($visit->car_type != 'T') {
            return 'This Visit Is Not Of Type Truck';
        }


        if (empty($visit->shipment_id)) {
            return 'This Visit Does Not Have Shipment ID';
        }


        if (empty($visit->shipment_number)) {
            return 'This Visit Does Not Have Shipment Number';
        }


        return $visit;


//        $users = Employee::query()->pluck('id');
//        $users_arr = [3,40,44,45,46,47,49,50,51,52,53,54,55,56,57,58,59];
//
//        $visits = VisitingDetails::query()->whereNotIn('user_id',$users_arr)->get();
//        foreach ($visits as $visit ) {
//            $visit->delete();
//        }
//        return 'Done';

        //        $visit = VisitingDetails::query()
        //            ->with('empvisit')
        //            ->where('id',380)
        //            ->get();
        //
        //        return $visit->empvisit;

        //        $visits = VisitingDetails::query()->pluck('visitor_id');
        //
        //        $visitors = Visitor::query()->whereNotIn('id', $visits)->get();
        //        foreach ($visitors as $visitor) {
        //            $visitor->delete();
        //        }
        //
        //        return 'Done';


        //        $emps = Employee::query()->pluck('id');
        //
        //        $emps_to_choose = [ 3, 40, 44, 45, 46, 47, 49, 50, 51, 52 ];
        //
        //        $choose_from_employees = array(3,40,46,47,52);
        //        $array_of_employees = array(7.42,48);
        //        // return $choose_from_employees[array_rand($choose_from_employees,1)];
        //        // $array_of_employees[array_rand($array_of_employees , 1)] ;
        //
        //
        //        $visits = VisitingDetails::query()
        //        ->get(['id','creator_employee']);
        //
        //
        //        foreach ($visits as $visit) {
        //            $random = $emps_to_choose[array_rand($emps_to_choose,1)];
        //
        ////            if (in_array( $visit->creator_employee , $array_of_employees )) {
        ////                $visit->creator_employee = $choose_from_employees[array_rand($choose_from_employees,1)];
        ////                $visit->save();
        ////            }
        //
        //            $visit->creator_employee = $random ;
        //            $visit->save();
        //
        //        }
        //
        //        return 'Done';

        // Employees shopuld be exisited
        // 3 , 7 , 40  , 42 , 46 , 47 , 48 , 52

        // return auth()->user()->employee;


        //        $arr = array('T','C','P');
        //        $vistis = VisitingDetails::query()->get();
        //        foreach ($vistis as $visit) {
        //            if ($visit->creatorEmployee->level == 0) {
        //                $visit->approval_status = 2;
        //                $visit->save();
        //            }
        //
        //            if ($visit->creatorEmployee->level == 1 || $visit->creatorEmployee->level == 2) {
        //                $visit->approval_status = 0;
        //                $visit->save();
        //            }
        //
        //        }
        //        return 'Done';
    }
}