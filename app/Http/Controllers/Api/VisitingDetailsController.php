<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
class VisitingDetailsController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => $validator->errors(),'status' => 400 , 'msg' => 'Validation Error']);
        }
        $user = User::query()->where('email',$request->get('email'))->first();
        if (!$user) {
            return response()->json(['status' => 'There Is No Such email' ,'status',401]);
        }

        if (auth()->attempt($this->credentials($request))) {
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['token'=> $token , 'msg' => 'Login Success','status' => 200]);
        }

        return response()->json(['msg' =>'Password Is Incorrect','status'=>400]);
    }

    public function credentials($request)
    {
        return $request->only($this->username(),'password');
    }

    public function username()
    {
        return 'email';
    }

    public function getVisitDetails($visit_id)
    {
        if (!$visit_id || !is_numeric($visit_id)) {
            return response()->json(['msg'=> 'Visit ID Should Be An Integer' , 'status' => 400]);
        }

        $visit = VisitingDetails::query()->with('visitor')->with('companions')->find($visit_id);
        if (!$visit) {
            return response()->json(['msg'=> 'Visit Not Found' , 'status' => 400]);
        }

        return response()->json(['data' => $visit , 'status' => 200 , 'msg'=> 'Success']);
    }

}
