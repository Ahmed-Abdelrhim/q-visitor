<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;


//use niklasravnsborg\LaravelPdf\Facades\Pdf;
//use PDF;

class LogsController extends Controller
{
    public function index()
    {
        return view('admin.logs.index');
    }

    public function search(Request $request)
    {
        if (auth()->user()->employee->level == 0) {
            $date = Carbon::parse($request->input('logs_date'))->format('y-m-d');
            $start = Carbon::parse($date)->startOfDay();
            $end = Carbon::parse($date)->endOfDay();
            $visits = VisitingDetails::query()
                ->with('visitor:id,first_name,last_name')
                ->with('companions:id,first_name,last_name')
                ->with('shipment:id,name')
                ->whereBetween('checkin_at', [$start, $end])
                ->where(function ($query) {
                    $query->where('creator_employee', auth()->user()->employee->id)
                        ->orWhere('emp_one', auth()->user()->employee->id)
                        ->orWhere('creator_id', auth()->user()->id)
                        ->orWhere('emp_two', auth()->user()->employee->id);
                })
                ->get();

            return view('admin.logs.index', ['visits' => $visits, 'show_data' => true, 'logs_date' => $date]);
        }

        $notifications = array('message' => 'غير مسموح بعرض البيانات', 'alert-type' => 'info');
        return redirect()->back()->with($notifications);
    }

    public function downloadPdf($logs_date)
    {
        $start_of_day = Carbon::parse($logs_date)->startOfDay();
        $end_of_day = Carbon::parse($logs_date)->endOfDay();

        $visits = VisitingDetails::query()
            ->with('visitor:id,first_name,last_name')
            ->with('companions:id,first_name,last_name')
            ->with('shipment:id,name')
            ->whereBetween('checkin_at', [$start_of_day, $end_of_day])
            ->where(function ($query) {
                $query->where('creator_employee', auth()->user()->employee->id)
                    ->orWhere('emp_one', auth()->user()->employee->id)
                    ->orWhere('creator_id', auth()->user()->id)
                    ->orWhere('emp_two', auth()->user()->employee->id);
            })
            ->get();







        // Instantiate a new Dompdf object
        $pdf = new Dompdf();

        // Load the HTML view with Arabic data
        $html = view('admin.logs.pdf', compact('visits'))->render();

        $options = new \Dompdf\Options();
        // Set options to support Arabic characters
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Scheherazade');
        $options->set('fontHeightRatio', 0.7);
        $options->set('enable_font_subsetting', true);
        $options->set('chroot', public_path());




        // Set Dompdf options to support Arabic characters
        $pdf->setOptions($options);




        // Load the HTML into Dompdf
        $pdf->loadHtml($html);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Render the PDF
        $pdf->render();

        // Output the generated PDF to the browser
        return $pdf->stream('visit-report.pdf');



        //        if (count($visits) < 0 ) {
        //            $notifications = array('message' => 'Can Not Download Data' , 'alert-type' => 'info');
        //            return redirect()->back()->with($notifications);
        //        }
        //
        //        $pdf = PDF::loadView('admin.logs.pdf', array('visits' => $visits))
        //            ->setPaper('a4', 'portrait');
        //        return $pdf->download('visit-report.pdf');



    }

}



//            $dompdf = new Dompdf();
//            $dompdf->setPaper('A4', 'portrait');
//
//            // $pdf = PDF::loadView('admin.logs.pdf', array('visits' => $visits));
//
//            $html = '<html><body><p>مرحبا بالعالم</p></body></html>';
//            $dompdf->loadHtml($html);
//
//            // set the font
//            $dompdf->set_option('fontDir', '/path/to/fonts/');
//            $dompdf->set_option('fontCache', '/path/to/cache/');
//            $dompdf->set_option('defaultFont', 'Arial');
//
//            $dompdf->render();
//            return $dompdf->stream('visit-report.pdf');
//
//




//            $pdf = PDF::loadView('admin.logs.pdf', array('visits' =>  $visits));
//            return $pdf->stream('visit-report.pdf');


//        }