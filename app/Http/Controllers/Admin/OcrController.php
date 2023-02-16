<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

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
        $data ='false';
        if (isset($_POST['images'])) {
            $data = 'true';
        }
        // $images = explode("||", $_GET['images']);
        return response()->json(['data' => $data]);
        $name = explode(" ", $_GET['name']);

        // $name = null;
        //        if (isset($_GET['name'])) {
        //            $name = $_GET['name'];
        //        }

        $gender = null;
        if (isset($_GET['gender'])) {
            $gender = $_GET['gender'];
            $gender = 5;
            if ($gender == 'F') {
                $gender = 10;
            }
        }


        $address = null;
        if (isset($_GET['address'])) {
            $address = $_GET['address'];
        }

        $nat_id = null;
        if (isset($_GET['nat_id'])) {
            $nat_id = $_GET['nat_id'];
        }


        $checkin_date = null;
        if (isset($_GET['checkin_date'])) {
            $checkin_date = $_GET['checkin_date'];
        }


        $checkin_time = null;
        if (isset($_GET['checkin_time'])) {
            $checkin_time = $_GET['checkin_time'];
        }


        // $images = explode("||", $_GET['images']);
        //                if (isset($_GET['images'])) {
        //                    $images = explode("||", $_GET['images']);
        //
        //                    // TODO:: save images
        //
        //                    //            foreach ($images as $img) {
        //                    //                $img = str_replace("data:image/jpeg;base64,", "", $img);
        //                    //                if ($img != '' or $img != ' ') {
        //                    //                    file_put_contents('images/' . $nat_id . '-' . $counter . '.jpg', base64_decode($img));
        //                    //                    $counter++;
        //                    //                }
        //                    //            }
        //                }

        $perpic = null;
        if (isset($_GET['perpic'])) {
            $perpic = $_GET['perpic'];
        }

        $exdate = null;
        if (isset($_GET['exdate'])) {
            $exdate = $_GET['exdate'];
        }

        $plate_no = null;
        if (isset($_GET['plate_no'])) {
            $plate_no = $_GET['plate_no'];
        }

        $add = null;
        if (isset($_GET['add'])) {
            $add = $_GET['add'];
        }
        return response()->json(['data' => $name]);


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
    }

    public function playy()
    {
//        if (empty($this->new_request))
//            return 'Empty';
        return $this->new_request;
    }
}