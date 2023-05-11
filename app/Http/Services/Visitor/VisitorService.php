<?php

namespace App\Http\Services\Visitor;

use App\Enums\Status;
use App\Http\Requests\VisitorRequest;
use App\Jobs\BackgroundJob;
use App\Jobs\SqlServerJob;
use App\Models\Booking;
use App\Models\PreRegister;
use App\Models\Shipment;
use App\Models\Types;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use App\Notifications\SendVisitorToEmployee;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class VisitorService
{
    public function all()
    {
        $user = auth()->user();

        if ($user->hasRole(1)) {
            return VisitingDetails::query()
                ->with('visitor')
                ->with('companions')
                ->with('empvisit')
                ->orderBy('id', 'desc')
                ->get();
        }

        if (auth()->user()->hasRole(14) || auth()->user()->hasRole(15)) {
            return VisitingDetails::query()
                ->with('visitor')
                ->with('companions')
                ->with('empvisit')

                // Here The Difference
                ->where('car_type', 'T')
                ->orWhere('creator_id', $user->id)
                ->orWhere('user_id', $user->employee->id)
                ->orWhere('creator_employee', $user->employee->id)
                ->orWhere('emp_one', $user->employee->id)
                ->orWhere('emp_two', $user->employee->id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            return VisitingDetails::query()
                ->with('visitor')
                ->with('companions')
                ->with('empvisit')
                ->where('creator_id', $user->id)
                ->orWhere('user_id', $user->employee->id)
                ->orWhere('creator_employee', $user->employee->id)
                ->orWhere('emp_one', $user->employee->id)
                ->orWhere('emp_two', $user->employee->id)


                // ->orWhere('editor_id', $user->id)
                // ->orWhere('employee_id', $user->employee->id)
                ->orderBy('id', 'desc')
                ->get();
        }
    }


    public function find($id)
    {
        // if(auth()->user()->getrole->name == 'Employee') {


//        if (auth()->user()->hasRole('Employee')) {
//            return VisitingDetails::query()->where(['id' => $id, 'employee_id' => auth()->user()->employee->id])->first();
//        } else {
        return VisitingDetails::query()->find($id);
//        }
    }


    public function findWhere($column, $value)
    {
        return VisitingDetails::query()->where($column, $value)->get();
    }


    public function findWhereFirst($column, $value)
    {

        return VisitingDetails::query()->where($column, $value)->first();
    }


    public function paginate($perPage = 10)
    {
        return VisitingDetails::query()->paginate($perPage);
    }

    public function generateSubstituteQrCode($visit_id)
    {
        $strict_types = 1;

        require_once('./../vendor/autoload.php');

        $options = new QROptions(
            [
                'eccLevel' => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version' => 5,
            ]
        );

        return $qrcode = (new QRCode($options))->render(strval($visit_id));
    }


    public function make(VisitorRequest $request)
    {
        $visitor = DB::table('visiting_details')->orderBy('reg_no', 'desc')->first();
        $date = date('y-m-d');
        $data = substr($date, 0, 2);
        $data1 = substr($date, 3, 2);
        $data2 = substr($date, 6, 8);
        $flag = false;

        //        if ($visitor) {
        //            $value = substr($visitor->reg_no, -2);
        //            if ($value < 1000) {
        //                $reg_no = $data2 . $data1 . $data . ($value + 1);
        //            } else {
        //                $reg_no = $data2 . $data1 . $data . '01';
        //            }
        //        } else {
        //            $reg_no = $data2 . $data1 . $data . '01';
        //        }

        if ($visitor) {
            $reg_no = $visitor->reg_no + 1;
            $visit_id_for_qr_code = $visitor->id + 1;

        } else {
            $reg_no = rand(11111111, 99999999);
        }


        //        if (auth()->user()->hasRole(15) && $request->input('car_type' == 'T')) {
        //            if (empty($request->input('shipment_number')) || empty($request->input('shipment_id'))) {
        //                $notifications = array('message' => 'The Shipment Number & Shipment ID Can Not be Empty');
        //                return redirect()->back()->with($notifications);
        //            }
        //        }


        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['gender'] = $request->input('gender');
        // $input['address'] = strip_tags(trim($request->input('address')));
        $address = strip_tags(trim($request->input('address')));
        $address = str_replace('&nbsp;', '', $address);
        $address = str_replace('<p>;', '', $address);
        $address = str_replace('</p>', '', $address);
        $input['address'] = $address;
        $input['type'] = $request->input('type');
        $input['national_identification_no'] = $request->input('national_identification_no');
        $input['is_pre_register'] = false;
        $input['status'] = Status::ACTIVE;
        $visitor = Visitor::query()->create($input);


        if ($visitor) {
            $purpose = strip_tags(trim($request->input('purpose')));
            $purpose = str_replace('&nbsp;', '', $purpose);
            $purpose = str_replace('<p>;', '', $purpose);
            $purpose = str_replace('</p>;', '', $purpose);

            $visiting['purpose'] = $purpose;
            $visiting['reg_no'] = $reg_no;
            // $visiting['purpose'] = strip_tags(trim($request->input('purpose')));
            $visiting['company_name'] = $request->input('company_name');
            $visiting['employee_id'] = $request->input('employee_id');
            $visiting['checkin_at'] = $request->input('from_date');// date('y-m-d H:i');
            $visiting['visitor_id'] = $visitor->id;
            $visiting['status'] = Status::ACTIVE;


            // This Visit Is Going To This User
            $visiting['user_id'] = $request->input('employee_id');

            // $visiting['user_id'] = auth()->user()->id;


            // $visiting['creator_employee'] = auth()->user()->employee->id;
            $visiting['creator_employee'] = auth()->user()->employee->id;

            $emp_one = NULL;
            $emp_two = NULL;

            if (auth()->user()->employee->level == 1) {
                $emp_one = auth()->user()->employee->emp_one;
            }

            if (auth()->user()->employee->level == 2) {
                $emp_one = auth()->user()->employee->emp_one;
                $emp_two = auth()->user()->employee->emp_two;
            }


            if (auth()->user()->employee->level == 0) {
                if ($request->input('car_type') == 'P' || $request->input('car_type') == 'C') {

                    $visiting['approval_status'] = 2;
                    // $visiting['approval_status']  =  1;
                    $flag = true;
                } else {

                    $visiting['approval_status'] = 0;

                }
            }


            $visiting['emp_one'] = $emp_one;
            $visiting['emp_two'] = $emp_two;

            //$visiting['qrcode'] = $request->input('qrcode');

            // $visiting['expiry_date'] = Carbon::parse( $request->input('expiry_date'));
            $visiting['expiry_date'] = date('Y-m-d H:i:s', strtotime($request->input('expiry_date')));

            // $visiting['from_date'] =  Carbon::parse($request->input('from_date'));
            $visiting['from_date'] = date('Y-m-d H:i:s', strtotime($request->input('from_date')));


            $visiting['type_id'] = $request->input('type');
            $visiting['car_type'] = $request->input('car_type');
            $visiting['is_new_scan'] = 0;


            $visitingDetails = VisitingDetails::query()->create($visiting);

            $visit = VisitingDetails::query()->with('visitor')->orderBy('id', 'desc')->first();
            try {
                $data = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . $visit->id);
                $visit->qrcode = $data;
                $visit->save();

            } catch (\Exception $e) {
                //                try {
                //                    $data = $this->generateSubstituteQrCode($visit->id);
                //                    $visit->qrcode = $data;
                //                    $visit->save();
                //                } catch (\Exception $e) {
                //                    $visiting['qrcode'] = NULL;
                //                }
                $visiting['qrcode'] = NULL;
            }


            if ($flag) {
                // Here The Visit Should Be Accepted Because The User Created The Visit Is Admin Or Does Not Have Managaers
                // Accept The Visit
                $visit->approval_status = 2;
                $visit->save();

                // Sending Qr Code And Sms
                $job = BackgroundJob::dispatch($visit);
            }

            if ($request->file('image')) {
                $visitingDetails->addMedia($request->file('image'))->toMediaCollection('visitor');
            }

            try {
                $visitingDetails->employee->user()->notify(new SendVisitorToEmployee($visitingDetails));
            } catch (\Exception $e) {
            }
        } else {
            $visitingDetails = '';
        }

        if (setting('notifications_email') == 1) {
            $email = $input['email'];
            $name = $input['first_name'] . ' ' . $input['last_name'];
            $id = $input['national_identification_no'];
            $phone = $input['phone'];
            $fromdate = date('y-m-d');
            $todate = date('y-m-d', time() + 86400);
            $time = date('H:i');
            $vid = $visiting['visitor_id'];

            try {
                // Here Send To Sql Server Database The ID of The Visit And The Visitor Name

//                DB::connection('sqlsrv')
//                    ->statement("INSERT INTO visits  (visit_id, visitor_name , date_from , date_to , flag) VALUES ( " . $visit->id . " ,'" . $visit->visitor->name . "' , '". $visit->checkin_at."' , '".  $visit->expiry_date ."' , 1 );" );


                DB::connection('sqlsrv')
                    ->statement("INSERT INTO visits  (visit_id, visitor_name , date_from , date_to , flag)
                                        VALUES ( " . $visit->id . " , N' " . $visit->visitor->name . " ' , '" . $visit->checkin_at . "' , '" . $visit->expiry_date . "' , 1 );");

                // $sql = SqlServerJob::dispatch($visit->id, $visit->visitor->name, $visit->checkin_at, $visit->expiry_date );

                // $sql = SqlServerJob::dispatch($visit->id , $visit->visitor->name );


            } catch (\Exception $e) {}



            return $visitingDetails;

        }
    }

    public function update(Request $request, $id)
    {
        $visitingDetails = VisitingDetails::query()->findOrFail($id);
        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['gender'] = $request->input('gender');
        $address = strip_tags(trim($request->input('address')));
        $address = str_replace('&nbsp;', '', $address);
        $address = str_replace('<p>;', '', $address);
        $address = str_replace('</p>;', '', $address);
        $input['address'] = $address;
        // $input['address'] = strip_tags(trim($request->input('address')));
        $input['type'] = $request->input('type');
        $input['national_identification_no'] = $request->input('national_identification_no');
        $input['is_pre_register'] = false;
        $input['status'] = Status::ACTIVE;

        if ($visitingDetails->qrcode == 'storage/qrcode/1234.png' || empty($visitingDetails->qrcode)) {
            //            $url = 'https://www.qudratech-eg.net/qrcode/index.php?data=' . $visitingDetails->id;
            //            $data = file_get_contents($url);

            $data = Http::get('https://www.qudratech-eg.net/qrcode/index.php?data=' . $visitingDetails->id);


            $visiting['qrcode'] = $data;
        }

        $visitingDetails->visitor->update($input);

        if ($visitingDetails) {
            $purpose = strip_tags(trim($request->input('purpose')));
            $purpose = str_replace('&nbsp;', '', $purpose);
            $purpose = str_replace('<p>;', '', $purpose);
            $purpose = str_replace('</p>', '', $purpose);
            $visiting['purpose'] = $purpose;
            $visiting['company_name'] = $request->input('company_name');
            $visiting['employee_id'] = $request->input('employee_id');
            $visiting['visitor_id'] = $visitingDetails->visitor->id;
            $visiting['status'] = Status::ACTIVE;

            $visiting['user_id'] = $request->input('employee_id');

            // $visiting['qrcode'] = $request->input('qrcode');

            // $visiting['expiry_date'] = $request->input('expiry_date');
            // $visiting['expiry_date'] = Carbon::parse($request->input('expiry_date'))->format('Y-m-d H:i:s');
            $visiting['expiry_date'] = date('Y-m-d H:i:s', strtotime($request->input('expiry_date')));


            $visiting['from_date'] = $request->input('from_date');
            $visiting['type_id'] = $request->input('type');


            $visiting['car_type'] = $request->input('car_type');


            if (!auth()->user()->hasRole(15)) {
                $visiting['shipment_number'] = $visitingDetails->shipment_number;
                $visiting['shipment_id'] = $visitingDetails->shipment_id;
                $visiting['quality_check'] = $visitingDetails->quality_check;
            } else {
                $visiting['shipment_number'] = $request->input('shipment_number');
                $visiting['shipment_id'] = $request->input('shipment_id');
                $shipment = Shipment::query()->find($request->input('shipment_id'));
                // Qulaity_check
                if ($shipment) {
                    $quality_check = $shipment->quality_check;
                    if ($quality_check == 0) {
                        $visiting['quality_check'] = 2;
                    }
                    if ($quality_check == 1) {
                        $visiting['quality_check'] = 1;
                    }

                }
            }
            $visitingDetails->update($visiting);
        }

        if ($request->file('image')) {
            $visitingDetails->addMedia($request->file('image'))->toMediaCollection('visitor');
        }
        try {
            $sms = file_get_contents("https://www.qudratech-sd.com/sms_api.php?mob=" . $input['phone']);
            $visitingDetails->employee->user()->notify(new SendVisitorToEmployee($visitingDetails));

        } catch (\Exception $e) {
            // Using a generic exception·..

        }
        //        try {
        //            $data = file_get_contents("https://qudratech-eg.net/mail/tt.php?vid=" . $visiting['visitor_id'] . "&name=" . $input['first_name']);
        //            $sms = file_get_contents("https://www.qudratech-sd.com/sms_api.php?mob=" . $input['phone']);
        //        } catch (\Exception $e) {
        //            $notification = array('message' => 'Message was not sent', 'alert-type' => 'info');
        //            return redirect()->back()->with($notification);
        //        }
        return $visitingDetails;
    }

    /**
     * @param $id
     * @return mixed
     */
    public
    function delete($id)
    {
        return VisitingDetails::find($id)->delete();
    }


}


// Check If The Visit Detail Type Level Is 0 Or Not
// $type_id = $visitingDetails->visitor->type;
//                if ($type_id) {
//                    $type = Types::query()->find($type_id);
//                    if ($type) {
//                        if ($type->level == 0) {
//                            $visitingDetails->approval_status == 2;
//                            $visitingDetails->save();
//                        }
//                    }
//                }


// if(auth()->user()->getrole->name == 'Employee') {

//        if (auth()->user()->hasRole('Employee')) {
//            return VisitingDetails::query()->where(['employee_id' => auth()->user()->employee->id])->orderBy('id', 'desc')->get();
//        } else {
//            return VisitingDetails::query()->orderBy('id', 'desc')->get();
//        }


//        if (auth()->user()->hasRole(14) ) {
//            return VisitingDetails::query()
//                ->Where('user_id', $user->id)
//                ->where(function ($query) {
//                    $query->where('car_type', 'T')
//                        ->where_not_null('shipment_number')
//                        ->where_not_null('shipment_id');
//                })
//                ->orWhere('creator_employee', $user->employee->id)
//                ->orWhere('emp_one', $user->employee->id)
//                ->orWhere('emp_two', $user->employee->id)
//                ->orWhere('creator_id', $user->id)
//                ->orderBy('id', 'desc')
//                ->get();
//        }


// if (!$user->hasRole(1) && !$user->hasRole(14)) {
// Return Only The VisitingDetails Created By This User Or Edit By The Current User
//            return VisitingDetails::query()
//                ->with('visitor')
//                ->with('companions')
//                ->where('creator_id', $user->id)
//                ->orWhere('emp_one', $user->employee->id)
//                ->orWhere('emp_two' ,$user->employee->id)
//                ->orWhere('editor_id', $user->id)
//                ->orWhere('employee_id', $user->employee->id)
//                ->orWhere('user_id', $user->id)
//
//                ->orderBy('id', 'desc')
//                ->get();
//        }
//        else {
//            // The User Is Of Type ADMIN So Return All The VisitingDetails
//            return VisitingDetails::query()->with('visitor')->with('companions')->orderBy('id', 'desc')->get();
//        }










// $dt = json_encode('name:'.$name.',id:'.$id.',phone:'.$phone.',fdate:'.$fromdate.',todate:'.$todate.',ftime:'.$time.',mail:'.$email);



// if ($visitingDetails->type->level == 0) {


//            if (auth()->user()->employee->level == 0) {
//                try {
//                    $job = BackgroundJob::dispatch($visitingDetails);
//                    // $send_mail = Http::get('https://qudratech-eg.net/mail/tt.php?vid=' . $visitingDetails->visitor->id);
//                    // $send_sms = Http::get('https://www.qudratech-eg.net/sms_api.php?mob=' . $visitingDetails->visitor->phone);
//                } catch
//                (\Exception $e) {
//                    $notification = array('message' => 'Message was not sent', 'alert-type' => 'info');
//                    return redirect()->back()->with($notification);
//                }
//            }
