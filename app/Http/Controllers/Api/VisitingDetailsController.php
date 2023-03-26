<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitngDetailsResource;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\User;

class VisitingDetailsController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
            'joining_date' => 'nullable|date',
            'gender' => ['required', 'string', 'max:6', 'regex:(female|Female|male|Male|MALE|FEMALE)'],
            'level' => 'required|between:0,2',
            'emp_one' => 'nullable|numeric|exists:employees,id',
            'emp_two' => 'nullable|numeric|exists:employees,id',
            'role' => 'required|numeric|exists:roles,id',
            'department' => 'required|numeric|exists:departments,id',
            'designation' => 'required|numeric|exists:designation,id',
            'about' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => $validator->errors()], 400, ['msg' => 'Validation Error']);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            // return response()->json(['data' => $validator->errors(),'status' => 400 , 'msg' => 'Validation Error']);
            return response()->json(['data' => $validator->errors()], 400, ['msg' => 'Validation Error']);
        }
        $user = User::query()->where('email', $request->get('email'))->first();
        if (!$user) {
            // return response()->json(['status' => 'There Is No Such email' ,'status',401]);
            return response()->json(['data' => 'There Is No Such email'], 400, ['msg' => 'Not Valid']);
        }

        if (auth()->attempt($this->credentials($request))) {
            $token = $user->createToken('authToken')->plainTextToken;
            // return response()->json(['token'=> $token , 'msg' => 'Login Success','status' => 200]);
            return response()->json(['token' => $token, 'msg' => 'Login Success', 'status' => 200]);
        }
        // return response()->json(['msg' =>'Password Is Incorrect','status'=>400]);
        return response()->json(['data' => 'Password Is Incorrect'], 400, []);
    }

    public function credentials($request)
    {
        return $request->only($this->username(), 'password');
    }

    public function username()
    {
        return 'email';
    }

    public function getVisitDetails($visit_id)
    {
        if (!$visit_id || !is_numeric($visit_id)) {
            return response()->json(['msg' => 'Visit ID Should Be An Integer', 'status' => 400]);
        }

        $visit = VisitingDetails::query()->with('visitor')->with('companions')->find($visit_id);
        if (!$visit) {
            return response()->json(['msg' => 'Visit Not Found', 'status' => 400]);
        }

        // $visit = new VisitngDetailsResource($visit);

        return response()->json(['data' => $visit, 'status' => 200, 'msg' => 'Success']);
    }

    public function logout(Request $request)
    {
        $user = $request->user()->first_name;
        $request->user()->currentAccessToken()->delete();
        return response()->json(['msg' => 'Good Bye Hope See You Soon ' . $user, 'status' => 200]);
    }

    public function getTodayVisits()
    {
        // http://127.0.0.1:8000/api/getTodayVisits
        $date = Carbon::now();
        $start_of_day = Carbon::parse($date->startOfDay());
        $end_of_day = $date->endOfDay();
        $visits = VisitingDetails::query()
            ->with('visitor')
            ->whereBetween('created_at', [Carbon::parse($start_of_day), Carbon::parse($end_of_day)])
            ->get()
            ->pluck('visitor.name', 'id');
        return response()->json([$visits],200);
        // ->pluck('id','visitor.name');
        // ->where('created_at' , '>=' , Carbon::now())
        // ->whereRaw('date(created_at) = curdate()')
        // return Carbon::parse($start_of_day) . '<br>' . Carbon::parse($end_of_day);
    }


    public function lastSignedInVisit()
    {
        // Here You Should Call The Api
        $call = Http::get('127.0.0.1:8000/api/getLastSignedInVisit');
        $visit = VisitingDetails::query()
            ->with('visitor')
            ->with('companion')
            ->find($call);

        if (!$visit) {
            return response()->json(['data' => 'No Data Found'],404);
        }
        return response()->json(['data' => $visit],200);
    }
}
