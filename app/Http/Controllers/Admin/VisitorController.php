<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\VisitorRequest;
use App\Models\Employee;
use App\Models\Languages;
use App\Models\Types;
use App\Models\VisitingDetails;
use App\Http\Services\Visitor\VisitorService;
use App\Models\Visitor;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Twilio\Rest\Client;

class VisitorController extends Controller
{
    protected $visitorService;

    public function __construct(VisitorService $visitorService)
    {
        $this->visitorService = $visitorService;

        $this->middleware('auth');
        $this->data['sitetitle'] = 'Visitors';

        $this->middleware(['permission:visitors'])->only('index');
        $this->middleware(['permission:visitors_create'])->only('create', 'store');
        $this->middleware(['permission:visitors_edit'])->only('edit', 'update');
        $this->middleware(['permission:visitors_delete'])->only('destroy');
        $this->middleware(['permission:visitors_show'])->only('show');
    }


    public function index(Request $request)
    {
        // return auth()->user()->getRole()->first()->name;
        return view('admin.visitor.index');
    }


    public function create(Request $request)
    {

        $this->data['employees'] = Employee::where('status', Status::ACTIVE)->get();
        $this->data['types'] = Types::where('status', Status::ACTIVE)->get();

        return view('admin.visitor.create', $this->data);
    }

    public function store(VisitorRequest $request)
    {
        // return $request;
        $this->visitorService->make($request);
        return redirect()->route('admin.visitors.index')->withSuccess('The data inserted successfully!');
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
        $this->data['visitingDetails'] = $this->visitorService->find($id);
        if ($this->data['visitingDetails']) {
            return view('admin.visitor.show', $this->data);
        } else {
            return redirect()->route('admin.visitors.index');
        }
    }

    public function edit($id)
    {
        $this->data['employees'] = Employee::where('status', Status::ACTIVE)->get();
        $this->data['types'] = Types::where('status', Status::ACTIVE)->get();
        $this->data['visitingDetails'] = $this->visitorService->find($id);
        if ($this->data['visitingDetails']) {
            return view('admin.visitor.edit', $this->data);
        } else {
            return redirect()->route('admin.visitors.index');
        }
    }

    public function update(VisitorRequest $request, VisitingDetails $visitor)
    {
        $this->visitorService->update($request, $visitor->id);
        // $notifications = array('message' => 'The data updated successfully!', 'alert-type' => 'success');
        return redirect()->route('admin.visitors.index')->withSuccess('The data updated successfully!');
        // return redirect()->route('admin.visitors.index')->with($notifications);
    }

    public function destroy($id)
    {
        $this->visitorService->delete($id);
        //return route('admin.visitors.index')->withSuccess('The data delete successfully!');
        return redirect()->route('admin.visitors.index')->withSuccess('The data delete successfully!');
    }


    public function getVisitor(Request $request)
    {
        $visitingDetails = $this->visitorService->all();
        $i = 1;
        $visitingDetailArray = [];
        if (!blank($visitingDetails)) {
            foreach ($visitingDetails as $visitingDetail) {
                $visitingDetailArray[$i] = $visitingDetail;
                $visitingDetailArray[$i]['setID'] = $i;
                $i++;
            }
        }
        return Datatables::of($visitingDetailArray)
            ->addColumn('action', function ($visitingDetail) {
                $retAction = '';
                $approve = false;

                if (auth()->user()->hasRole('Admin')) {
                    $approve = true;
                } else {
                    $type = Types::query()->find($visitingDetail->visitor->type);
                    if ($type) {
                        $role = Role::query()->find($type->role_one);
                        if ($role) {
                            if (auth()->user()->hasRole($role->name))
                                $approve = true;
                        }
                    }
                }

                if ($approve) {
                    $msg = __('files.Approve & send sms');
                    if ($visitingDetail->sent_sms_before == 1) {
                        $msg = __('files.Re-send sms');
                    }
                    $retAction .= '<a href="' . route('admin.visitors.send.sms', $visitingDetail) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                }

                if (auth()->user()->can('visitors_show')) {
                    $retAction .= '<a href="' . route('admin.visitors.show', $visitingDetail) . '" class="btn btn-sm btn-icon mr-2 show float-left btn-info actions"
data-toggle="tooltip" data-placement="top" title="' . __('files.View') . '"><i class="far fa-eye"></i></a>';
                }

                if (auth()->user()->can('visitors_edit')) {
                    $retAction .= '<a href="' . route('admin.visitors.edit', $visitingDetail) . '" class="btn btn-sm btn-icon float-left btn-primary actions"
data-toggle="tooltip" data-placement="top" title="' . __('files.Edit') . '">
 <i class="far fa-edit"></i></a>';
                }


                if (auth()->user()->can('visitors_delete')) {
                    $retAction .= '<form class="float-left pl-2" action="' . route('admin.visitors.destroy', $visitingDetail) . '" method="POST">' . method_field('DELETE') . csrf_field() . '<button class="btn btn-sm btn-icon btn-danger actions"
data-toggle="tooltip" data-placement="top" title="' . __('files.Delete') . '">
 <i class="fa fa-trash"></i></button></form>';
                }

                return $retAction;
                // return $visitingDetail->visitor->type;
            })
            ->editColumn('name', function ($visitingDetail) {
                return Str::limit($visitingDetail->visitor->name, 50);
            })
            ->editColumn('qrcode', function ($visitingDetail) {
                return $visitingDetail->qrcode;
            })
            ->addColumn('image', function ($visitingDetail) {
                // if (str_contains($visitingDetail->visitor->photo, 'data:image')) {
//                if (str_contains($visitingDetail->visitor->images, 'data:image')) {
//                    return '<figure class="avatar mr-2"><img src="' . $visitingDetail->visitor->photo . '" alt=""></figure>';
//                } else {
                // return '<figure class="avatar mr-2"><img src="https://www.qudratech-eg.net/visitorpass/public/' . $visitingDetail->visitor->photo . '" alt=""></figure>';
                return '<figure class="avatar mr-2"><img src="' . $visitingDetail->images . '" alt=""></figure>';
                // return $visitingDetail->getFirstMediaUrl('visitor');
//                }
            })
            ->editColumn('visitor_id', function ($visitingDetail) {
                return $visitingDetail->reg_no;
            })
            ->editColumn('email', function ($visitingDetail) {
                return Str::limit($visitingDetail->visitor->email, 50);
            })
            ->editColumn('phone', function ($visitingDetail) {
                return Str::limit($visitingDetail->visitor->phone, 50);
            })
            ->editColumn('employee_id', function ($visitingDetail) {
                return $visitingDetail->employee->user->name;
            })
            ->editColumn('date', function ($visitingDetail) {
                return date('d-m-Y h:i A', strtotime($visitingDetail->checkin_at));
            })
            ->addColumn('status', function ($visitingDetail) {
                if ($visitingDetail->sent_sms_before == 0)
                    return __('files.Pending');
                return __('files.Approved');
            })
            ->editColumn('id', function ($visitingDetail) {
                return $visitingDetail->setID;
            })
            ->rawColumns(['name', 'action'])
            ->escapeColumns([])
            ->make(true);
        var_dump($visitingDetail);
    }

    public
    function sendSms($visitingDetail_id)
    {
        $visit_details = VisitingDetails::query()->find($visitingDetail_id);
        $user_id = $visit_details->visitor_id;
        $user = Visitor::query()->find($user_id);
        if (!$user) {
            $notifications = array('error' => 'User Was Not Found');
            return redirect()->back()->with($notifications);
        }
        if (empty($user->phone)) {
            $notifications = array('error' => 'User phone number can not be empty');
            return redirect()->back()->with($notifications);
        }

        try {
            $send_mail = Http::get('https://qudratech-eg.net/mail/tt.php?vid=' . $user->id);
            $send_sms = Http::get('https://www.qudratech-eg.net/sms_api.php?mob=' . $user->phone);
        } catch (\Exception $e) {
            $notifications = array('error' => 'Something Went Wrong');
            return redirect()->back()->with($notifications);
        }

        if ($send_sms->status() == 200) {
            $notifications = array('success' => __('files.Success Transaction'));
            $visit_details->sent_sms_before = 1;
            $visit_details->save();
            return redirect()->back()->with($notifications);
        } else {
            $notifications = array('error' => 'Something Went Wrong');
            return redirect()->back()->with($notifications);
        }

    }

    public
    function play(string $locale)
    {
        return auth()->user()->employee();
    }
}
