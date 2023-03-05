<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\BackendController;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\PreRegister;
use App\Models\Sale;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use Illuminate\Http\Request;

class DashboardController extends BackendController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['sitetitle'] = 'Dashboard';

        $this->middleware(['permission:dashboard'])->only('index');
    }

    public function index()
    {

        //        if(auth()->user()->getrole->name == 'Employee') {
        //            $visitors       = VisitingDetails::query()->where(['employee_id'=>auth()->user()->employee->id])->orderBy('id', 'desc')->get();
        //            $preregister    = PreRegister::query()->where(['employee_id'=>auth()->user()->employee->id])->orderBy('id', 'desc')->get();
        //            $totalEmployees = 0;
        //        } else {
        //            $visitors       = VisitingDetails::orderBy('id', 'desc')->get();
        //            $preregister    = PreRegister::orderBy('id', 'desc')->get();
        //            $employees      = Employee::orderBy('id', 'desc')->get();
        //            $totalEmployees = count($employees);
        //        }
        $user = auth()->user();

        if ($user->hasRole(1)) {
            $visitors = VisitingDetails::orderBy('id', 'desc')->get();
            $preregister = PreRegister::orderBy('id', 'desc')->get();
            $employees = Employee::orderBy('id', 'desc')->get();
            $totalEmployees = count($employees);
        } else {
            $visitors = VisitingDetails::query()
                ->where('creator_id', $user->id)
                ->orWhere('editor_id', $user->id)
                ->orWhere('employee_id', $user->employee->id)
                ->orWhere('user_id', $user->id)
                ->with('type')
                ->get();
            $preregister = PreRegister::query()->where(['employee_id' => auth()->user()->employee->id])->orderBy('id', 'desc')->get();
            $totalEmployees = 0;
        }
        // print_r($visitors);

        $totalVisitor = Visitor::query()->count();
        $totalPrerigister = count($preregister);


        $this->data['totalVisitor'] = $totalVisitor;
        $this->data['totalEmployees'] = $totalEmployees;
        $this->data['totalPrerigister'] = $totalPrerigister;
        $this->data['visitors'] = $visitors;

        return view('admin.dashboard.index', $this->data);
    }


}
