<?php

namespace App\Http\Services\Visitor;

use App\Enums\Status;
use App\Http\Requests\VisitorRequest;
use App\Models\Booking;
use App\Models\PreRegister;
use App\Models\Types;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use App\Notifications\SendVisitorToEmployee;
use Illuminate\Http\Request;
use DB;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class VisitorService
{

    public function all()
    {
        $user = auth()->user();
        if (!$user->hasRole(1)) {
            // Return Only The VisitingDetails Created By This User Or Edit By The Current User
            return VisitingDetails::query()
                ->where('creator_id', $user->id)
                ->orWhere('emp_one', $user->employee->id)
                ->orWhere('emp_two' ,$user->employee->id)
                ->orWhere('editor_id', $user->id)
                ->orWhere('employee_id', $user->employee->id)
                ->orWhere('user_id', $user->id)
                ->with('type')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            // The User Is Of Type ADMIN So Return All The VisitingDetails
            return VisitingDetails::query()->with('type')->orderBy('id', 'desc')->get();
        }
    }


    public function find($id)
    {
        // if(auth()->user()->getrole->name == 'Employee') {
        if (auth()->user()->hasRole('Employee')) {
            return VisitingDetails::query()->where(['id' => $id, 'employee_id' => auth()->user()->employee->id])->first();
        } else {
            return VisitingDetails::query()->find($id);
        }
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


    public function make(VisitorRequest $request)
    {
        $visitor = DB::table('visiting_details')->orderBy('reg_no', 'desc')->first();
        $date = date('y-m-d');
        $data = substr($date, 0, 2);
        $data1 = substr($date, 3, 2);
        $data2 = substr($date, 6, 8);

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
        } else {
            $reg_no = rand(11111111, 99999999);
        }


        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['gender'] = $request->input('gender');
        // $input['address'] = strip_tags(trim($request->input('address')));
        $address = strip_tags(trim($request->input('address')));
        $address = str_replace('&nbsp;', '', $address);
        $input['address'] = $address;
        $input['type'] = $request->input('type');
        $input['national_identification_no'] = $request->input('national_identification_no');
        $input['is_pre_register'] = false;
        $input['status'] = Status::ACTIVE;
        $visitor = Visitor::query()->create($input);


        if ($visitor) {
            $purpose = strip_tags(trim($request->input('purpose')));
            $purpose = str_replace('&nbsp;', '', $purpose);
            $visiting['purpose'] = $purpose;
            $visiting['reg_no'] = $reg_no;
            // $visiting['purpose'] = strip_tags(trim($request->input('purpose')));
            $visiting['company_name'] = $request->input('company_name');
            $visiting['employee_id'] = $request->input('employee_id');
            $visiting['checkin_at'] = $request->input('from_date');// date('y-m-d H:i');
            $visiting['visitor_id'] = $visitor->id;
            $visiting['status'] = Status::ACTIVE;
            $visiting['user_id'] = $request->input('employee_id');
            $visiting['creator_employee'] = auth()->user()->employee->id;

            $emp_one = NULL;
            $emp_two = NULL;

            if (auth()->user()->employee->level == 1 ) {
                $emp_one =  auth()->user()->employee->emp_one;
            }

            if (auth()->user()->employee->level == 2) {
                $emp_one =  auth()->user()->employee->emp_one;
                $emp_two = auth()->user()->employee->emp_two;
            }



            $visiting['emp_one'] = $emp_one;
            $visiting['emp_two'] = $emp_two;

            //$visiting['qrcode'] = $request->input('qrcode');
            $url = 'https://www.qudratech-eg.net/qrcode/index.php?data=' . $input['first_name'] . $visitor->id;

            $data = file_get_contents($url);
            $visiting['qrcode'] = $data;

            // $visiting['expiry_date'] = Carbon::parse( $request->input('expiry_date'));
            $visiting['expiry_date'] = date('Y-m-d H:i:s', strtotime($request->input('expiry_date')));

            // $visiting['from_date'] =  Carbon::parse($request->input('from_date'));
            $visiting['from_date'] =   date('Y-m-d H:i:s', strtotime($request->input('from_date')));


            $visiting['type_id'] = $request->input('type');
            $visitingDetails = VisitingDetails::query()->create($visiting);

            if ($request->file('image')) {
                $visitingDetails->addMedia($request->file('image'))->toMediaCollection('visitor');
            }

            try {
                $visitingDetails->employee->user()->notify(new SendVisitorToEmployee($visitingDetails));
            } catch (\Exception $e) {
                // Using a generic exception

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
            //$dt = json_encode('name:'.$name.',id:'.$id.',phone:'.$phone.',fdate:'.$fromdate.',todate:'.$todate.',ftime:'.$time.',mail:'.$email);

            // if ($visitingDetails->type->level == 0) {
            if (auth()->user()->employee->level == 0) {
                try {
                    $send_mail = Http::get('https://qudratech-eg.net/mail/tt.php?vid=' . $visitingDetails->visitor->id);
                    $send_sms = Http::get('https://www.qudratech-eg.net/sms_api.php?mob=' . $visitingDetails->visitor->phone);
                } catch
                (\Exception $e) {
                    $notification = array('message' => 'Message was not sent', 'alert-type' => 'info');
                    return redirect()->back()->with($notification);
                }
            }


            return $visitingDetails;

        }
    }

    /**
     * @param $id
     * @param VisitorRequest $request
     * @return mixed
     */
    public
    function update(Request $request, $id)
    {
        $visitingDetails = VisitingDetails::query()->findOrFail($id);

        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['gender'] = $request->input('gender');
        $address = strip_tags(trim($request->input('address')));
        $address = str_replace('&nbsp;', '', $address);
        $input['address'] = $address;
        // $input['address'] = strip_tags(trim($request->input('address')));
        $input['type'] = $request->input('type');
        $input['national_identification_no'] = $request->input('national_identification_no');
        $input['is_pre_register'] = false;
        $input['status'] = Status::ACTIVE;
        $visitingDetails->visitor->update($input);

        if ($visitingDetails) {
            $purpose = strip_tags(trim($request->input('purpose')));
            $purpose = str_replace('&nbsp;', '', $purpose);
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






            $visitingDetails->update($visiting);
        }

        if ($request->file('image')) {
            $visitingDetails->addMedia($request->file('image'))->toMediaCollection('visitor');
        }
        try {
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
