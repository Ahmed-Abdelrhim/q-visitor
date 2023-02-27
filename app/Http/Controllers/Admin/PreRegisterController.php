<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreRegisterRequest;
use App\Models\Employee;
use App\Models\PreRegister;
use App\Models\VisitingDetails;
use App\Models\Visitor;
use App\Http\Services\PreRegister\PreRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PreRegisterController extends Controller
{
    protected $preRegisterService;

    public function __construct(PreRegisterService $preRegisterService)
    {
        $this->preRegisterService = $preRegisterService;

        $this->middleware('auth');
        $this->data['sitetitle'] = 'Pre-registers';
        $this->middleware(['permission:pre-registers'])->only('index');
        $this->middleware(['permission:pre-registers_create'])->only('create', 'store');
        $this->middleware(['permission:pre-registers_edit'])->only('edit', 'update');
        $this->middleware(['permission:pre-registers_delete'])->only('destroy');
        $this->middleware(['permission:pre-registers_show'])->only('show');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.pre-register.index');
    }

    public function create(Request $request)
    {
        if (auth()->user()->getrole->name == 'Employee') {
            $this->data['employees'] = Employee::where(['status' => Status::ACTIVE, 'id' => auth()->user()->employee->id])->get();
        } else {
            $this->data['employees'] = Employee::where('status', Status::ACTIVE)->get();
        }

        return view('admin.pre-register.create', $this->data);
    }

    public function store(PreRegisterRequest $request)
    {
        // return $request;
        $this->preRegisterService->make($request);
        return redirect()->route('admin.pre-registers.index')->withSuccess('The data inserted successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->data['preregister'] = $this->preRegisterService->find($id);
        if ($this->data['preregister']) {
            return view('admin.pre-register.show', $this->data);
        } else {
            return redirect()->route('admin.pre-registers.index');
        }
    }

    public function edit($id)
    {
        if (auth()->user()->getrole->name == 'Employee') {
            $this->data['employees'] = Employee::where(['status' => Status::ACTIVE, 'id' => auth()->user()->employee->id])->get();
        } else {
            $this->data['employees'] = Employee::where('status', Status::ACTIVE)->get();
        }
        $this->data['preregister'] = $this->preRegisterService->find($id);
        if ($this->data['preregister']) {
            return view('admin.pre-register.edit', $this->data);
        } else {
            return redirect()->route('admin.pre-registers.index');
        }
    }

    public function update(PreRegisterRequest $request, PreRegister $preRegister)
    {
        $this->preRegisterService->update($request, $preRegister->id);
        return redirect()->route('admin.pre-registers.index')->withSuccess('The data updated successfully!');
    }

    public function approvePreRegister($id)
    {
        $id = decrypt($id);
        $preRegister = PreRegister::query()->with('visitor')->find($id);
        if (!$preRegister) {
            $notifications = array('message' => 'Pre-Register Was Not Found', 'alert-type' => 'error');
            return redirect()->back()->with($notifications);
        }

        // TODO:: accept the pre-register to visitors
        // preregister was created and the visitor was created in [check-in/create-step-one form]
        //

        $visitor = VisitingDetails::query()->first();
        $date = date('y-m-d');
        $data = substr($date, 0, 2);
        $data1 = substr($date, 3, 2);
        $data2 = substr($date, 6, 8);

        if ($visitor) {
            $value = substr($visitor->reg_no, -2);
            if ($value < 1000) {
                $reg_no = $data2 . $data1 . $data . ($value + 1);
            } else {
                $reg_no = $data2 . $data1 . $data . '01';
            }
        } else {
            $reg_no = $data2 . $data1 . $data . '01';
        }

        // return $preRegister;
        // $this->createApprovedPreRegisterToVisitingDetails($reg_no);



        return $preRegister;
    }

//    public function createApprovedPreRegisterToVisitingDetails($preRegister,$reg_no)
//    {
//        // $url = 'https://www.qudratech-eg.net/qrcode/index.php?data=' . $input['first_name'] . $visitor->id;
//        // $data = file_get_contents($url);
//
//        try {
//            DB::beginTransaction();
//            VisitingDetails::query()->create([
//                'reg_no' => $reg_no,
//                'purpose' => '',
//                'company_name' => '',
//                'employee_id' => '',
//                'checkin_at' => '',
//                'visitor_id' => '',
//                'status' => Status::ACTIVE,
//                'qrcode' => '',
//                'expiry_date' => '',
//                'from_date' => '',
//            ]);
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();
//        }
//
//        //        $visiting['reg_no'] = $reg_no;
//        //        $visiting['purpose'] = $request->input('purpose');
//        //        $visiting['company_name'] = $request->input('company_name');
//        //        $visiting['employee_id'] = $request->input('employee_id');
//        //        $visiting['checkin_at'] = $request->input('from_date');//date('y-m-d H:i');
//        //        $visiting['visitor_id'] = $visitor->id;
//        //        $visiting['status'] = Status::ACTIVE;
//        //        $visiting['user_id'] = $request->input('employee_id');
//        //        //$visiting['qrcode'] = $request->input('qrcode');
//        //        $url = 'https://www.qudratech-eg.net/qrcode/index.php?data=' . $input['first_name'] . $visitor->id;
//        //        $data = file_get_contents($url);
//        //        $visiting['qrcode'] = $data;
//        //
//        //        $visiting['expiry_date'] = $request->input('expiry_date');
//        //        $visiting['from_date'] = $request->input('from_date');
//    }

    public function destroy($id)
    {
        $this->preRegisterService->delete($id);
        return route('admin.pre-registers.index')->withSuccess('The data delete successfully!');
    }


    public function getPreRegister(Request $request)
    {
        $pre_registers = $this->preRegisterService->all();
        $i = 1;
        $pre_registerArray = [];
        if (!blank($pre_registers)) {
            foreach ($pre_registers as $pre_register) {
                $pre_registerArray[$i] = $pre_register;
                $pre_registerArray[$i]['setID'] = $i;
                $i++;
            }
        }
        return Datatables::of($pre_registerArray)
            ->addColumn('action', function ($pre_register) {
                $retAction = '';

                //                if (auth()->user()->can('pre-registers_edit')) {
                //                    $retAction .= '<a href="' . route('admin.pre-registers.approve', encrypt($pre_register)) . '"
                //class="btn btn-sm btn-icon mr-2 accept float-left btn-success" data-toggle="tooltip" data-placement="top" title="Approve"><i class="far fa-check-circle"></i></a>';
                //                }

                if (auth()->user()->can('pre-registers_show')) {
                    $retAction .= '<a href="' . route('admin.pre-registers.show', $pre_register) . '" class="btn btn-sm btn-icon mr-2 show  float-left btn-info actions" data-toggle="tooltip" data-placement="top" title="View"><i class="far fa-eye"></i></a>';
                }

                if (auth()->user()->can('pre-registers_edit')) {
                    $retAction .= '<a href="' . route('admin.pre-registers.edit', $pre_register) . '" class="btn btn-sm btn-icon float-left btn-primary actions" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="far fa-edit"></i></a>';
                }


                if (auth()->user()->can('pre-registers_delete')) {
                    $retAction .= '<form class="float-left pl-2" action="' . route('admin.pre-registers.destroy', $pre_register) . '" method="POST">' . method_field('DELETE') . csrf_field() . '<button class="btn btn-sm btn-icon btn-danger actions" data-toggle="tooltip" data-placement="top" title="Delete"> <i class="fa fa-trash"></i></button></form>';
                }

                return $retAction;
            })
            ->editColumn('name', function ($pre_register) {
                return Str::limit($pre_register->visitor->name, 50);
            })
            ->editColumn('email', function ($pre_register) {
                return Str::limit($pre_register->visitor->email, 50);
            })
            ->editColumn('phone', function ($pre_register) {
                return Str::limit($pre_register->visitor->phone, 50);
            })
            ->editColumn('employee_id', function ($pre_register) {
                return $pre_register->employee->user->name;
            })
            ->editColumn('expected_date', function ($pre_register) {
                if ($pre_register->visitor->is_pre_register == 1) {
                    $date = '<p class="text-danger">' . $pre_register->expected_date . '</p>';
                } else {
                    $date = '<p>' . $pre_register->expected_date . '</p>';
                }
                return $date;
            })
            ->editColumn('expected_time', function ($pre_register) {
                if ($pre_register->visitor->is_pre_register == 1) {
                    $time = '<p class="text-danger">' . date('h:i A', strtotime($pre_register->expected_time)) . '</p>';
                } else {
                    $time = '<p>' . date('h:i A', strtotime($pre_register->expected_time)) . '</p>';
                }
                return $time;
            })
            ->editColumn('exit_date', function ($pre_register) {
                if ($pre_register->visitor->is_pre_register == 1) {
                    $date2 = '<p class="text-danger">' . $pre_register->exit_date . '</p>';
                } else {
                    $date2 = '<p>' . $pre_register->exit_date . '</p>';
                }
                return $date2;
            })
            ->editColumn('exit_time', function ($pre_register) {
                if ($pre_register->visitor->is_pre_register == 1) {
                    $time2 = '<p class="text-danger">' . date('h:i A', strtotime($pre_register->exit_time)) . '</p>';
                } else {
                    $time2 = '<p>' . date('h:i A', strtotime($pre_register->exit_time)) . '</p>';
                }
                return $time2;
            })
            ->editColumn('id', function ($pre_register) {
                return $pre_register->setID;
            })
            ->rawColumns(['name', 'action'])
            ->escapeColumns([])
            ->make(true);
    }

}
