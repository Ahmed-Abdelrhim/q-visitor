<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
    }

    public function search(Request $request)
    {
        $visit_id = $request->contractor;
        $workers = Worker::query()
            ->with('visit')
            ->with('visitor')
            ->where('visit_id', $visit_id)
            ->get();
        if (count($workers) <= 0 || empty($workers)) {
            $notifications = array('message' => __('files.There are no workers for this contractor'), 'alert-type' => 'info');
            return redirect()->back()->with($notifications);
        }
        return view('admin.ocr.contractor.workers', ['workers' => $workers]);
    }

    public function findWorkerWithNationalNum()
    {
        $name = null;
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }

        $nat_id = null;
        if (isset($_POST['nat_id'])) {
            $nat_id = $_POST['nat_id'];
        }

        $englishNationalNumber = $this->convertNationalNumberToEnglish($nat_id);

        $worker = Worker::query()
            ->where('nat_id', $englishNationalNumber)
            ->first();

        if (!$worker) {
            return 'Worker Was Not Found';
        }
        // ٢٩٨١٢٢٢٠١٠٢٥٧٦
        // 29812220102576

        $worker->is_scaned = 1;
        $worker->save();

        return response()->json(['status' => 200, 'msg' => 'Success' , 'nat_id' => $worker->nat_id]);
    }

    public function convertNationalNumberToEnglish($national_number): string
    {
        return strtr($national_number, array('۰' => '0', '۱' => '1',
            '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }
}
