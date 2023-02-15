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
            ->where('visitor_id',$id)
            ->first();
        return view('admin.ocr.print',['data'=>$data]);
    }

    public function ocrSave()
    {
        $gender = 10;
                if ($request->gender == 'M') {
                    $gender = 5;
                }

                if ($request->add == 'N') {
                    unlink(storage_path('app/public/' . 'plate.txt'));
                    $fh = fopen(storage_path('app/public/' . 'plate.txt'), 'w');
                    fclose($fh);
                } else {
                    $myfile = fopen(storage_path('app/public/' . 'plate.txt'), "w");
                    fwrite($myfile, $request->plate_no);
                }

    }
}