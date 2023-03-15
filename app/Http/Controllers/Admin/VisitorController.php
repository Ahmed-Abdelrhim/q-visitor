<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\VisitorRequest;
use App\Jobs\BackgroundJob;
use App\Models\Employee;
use App\Models\Languages;
use App\Models\Types;
use App\Models\VisitingDetails;
use App\Http\Services\Visitor\VisitorService;
use App\Models\Visitor;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Carbon;
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
        $this->data['employees'] = Employee::query()->where('status', Status::ACTIVE)->get();
        $this->data['types'] = Types::query()->where('status', Status::ACTIVE)->get();
        return view('admin.visitor.create', $this->data);
    }

    public function store(VisitorRequest $request)
    {
        $this->visitorService->make($request);
        return redirect()->route('admin.visitors.index')->withSuccess('The data inserted successfully!');
    }


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
        return redirect()->route('admin.visitors.index')->withSuccess('The data updated successfully!');
    }

    public function destroy($id)
    {
        $this->visitorService->delete($id);
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
                $status = 'Pending';

                // Implement Show Approve Button And The Status
                if ($visitingDetail->creatorEmployee->level == 0) {
                    // if (auth()->user()->hasRole(1) || auth()->user()->employee->id == $visitingDetail->creator_id ) {
                    // if (auth()->user()->hasRole(1) || auth()->user()->employee->id == $visitingDetail->creator_employee ) {
                    if (auth()->user()->hasRole(1) || auth()->user()->id == $visitingDetail->creator_id) {
                        $msg = __('files.Re-Send Sms');
                        $retAction .= '<a href="' . route('admin.visitors.send.sms', $visitingDetail) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                    }
                }

                if ($visitingDetail->creatorEmployee->level == 1) {
                    // if (auth()->user()->employee->id == $visitingDetail->creatorEmployee->emp_one || auth()->user()->hasRole(1)) {
                    if (auth()->user()->employee->id == $visitingDetail->emp_one || auth()->user()->hasRole(1)) {
                        if ($visitingDetail->approval_status == 0) {
                            $msg = 'Approve';
                            $retAction .= '<a href="' . route('admin.visit.approval', encrypt($visitingDetail->id)) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                        }
                        if ($visitingDetail->approval_status == 1) {
                            $msg = __('files.Re-Send Sms');
                            $retAction .= '<a href="' . route('admin.visitors.send.sms', $visitingDetail) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                        }
                    }


                }

                if ($visitingDetail->creatorEmployee->level == 2) {
                    if ($visitingDetail->approval_status == 0) {
                        // if (auth()->user()->employee->id == $visitingDetail->creatorEmployee->emp_one || auth()->user()->hasRole(1)) {
                        if (auth()->user()->employee->id == $visitingDetail->emp_one || auth()->user()->hasRole(1)) {
                            $msg = __('files.First Approval');
                            $retAction .= '<a href="' . route('admin.visit.approval', encrypt($visitingDetail->id)) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                        }
                    }
                    if ($visitingDetail->approval_status == 1) {
                        // if (auth()->user()->employee->id == $visitingDetail->creatorEmployee->emp_two || auth()->user()->hasRole(1)) {
                        if (auth()->user()->employee->id == $visitingDetail->emp_two || auth()->user()->hasRole(1)) {
                            $msg = __('files.Second Approval');
                            $retAction .= '<a href="' . route('admin.visit.approval', encrypt($visitingDetail->id)) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                        }
                    }
                    if ($visitingDetail->approval_status == 2) {
                        // if (auth()->user()->employee->id == $visitingDetail->creatorEmployee->emp_one || auth()->user()->hasRole(1)) {
                        if (auth()->user()->employee->id == $visitingDetail->emp_two || auth()->user()->hasRole(1)) {
                            $msg = __('files.Re-Send Sms');
                            $retAction .= '<a href="' . route('admin.visitors.send.sms', $visitingDetail) . '" class="btn btn-sm btn-icon mr-2 accept float-left btn-success actions" data-toggle="tooltip" data-placement="top" title="' . $msg . '"><i class="far fa-check-circle"></i></a>';
                        }
                    }
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

                if (count($visitingDetail->companions) > 0 ) {
                    $retAction .= '<a href="' . route('admin.visitors.companions', encrypt($visitingDetail->id)) . '" class="btn btn-sm btn-icon float-left btn-info actions"
                                    data-toggle="tooltip" data-placement="top" title="'.__('files.Companions').'" style="margin-left: 10px;">
                                    <i class="far fa-user"></i>
                                    </a>';
                }

                if (auth()->user()->can('visitors_delete')) {
                    $retAction .= '<form class="float-left pl-2" action="' . route('admin.visitors.destroy', $visitingDetail) . '" method="POST">' . method_field('DELETE') . csrf_field() . '<button class="btn btn-sm btn-icon btn-danger actions"
                                    data-toggle="tooltip" data-placement="top" title="' . __('files.Delete') . '">
                                    <i class="fa fa-trash"></i></button></form>';
                }

                return $retAction;
                // return $visitingDetail->type->level . ' Approval Status ' . $visitingDetail->approval_status;
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
                if ($visitingDetail->creatorEmployee->level == 0) {
                    // return $status = __('files.Approved');
                    return '<span class="Approved"  style="font-size: 15px"> Approved </span> ';
                }
                if ($visitingDetail->creatorEmployee->level == 1) {
                    if ($visitingDetail->approval_status == 0) {
                        // return $status = __('files.Pending');
                        return '<span class="Pending" style="font-size: 15px"> Pending </span> ';
                    }
                    if ($visitingDetail->approval_status == 1) {
                        // return $status = __('files.Approved');
                        return '<span class="Approved" style="font-size: 15px"> Approved </span> ';
                    }
                }
                if ($visitingDetail->creatorEmployee->level == 2) {
                    if ($visitingDetail->approval_status == 0) {
                        // return $status = __('files.Pending');
                        return '<span class="Pending" style="font-size: 15px"> Pending </span> ';
                    }
                    if ($visitingDetail->approval_status == 1) {
                        // return $status = __('files.Waiting Second Approval');
                        return '<span class="second-approve" style="font-size: 15px; display: flex; white-space: nowrap;"> Waiting Second Approval </span> ';
                    }
                    if ($visitingDetail->approval_status == 2) {
                        // return $status = __('files.Approved');
                        return '<span class="Approved"  style="font-size: 15px"> Approved </span> ';
                    }
                }
            })
            ->editColumn('id', function ($visitingDetail) {
                return $visitingDetail->setID;
            })
            ->rawColumns(['name', 'action'])
            ->escapeColumns([])
            ->make(true);
        // var_dump($visitingDetail);
    }

    public function companions($visit_id)
    {
        $visit_id = decrypt($visit_id);
        $visit = VisitingDetails::query()
            ->with('visitor')
            ->with('companions')
            ->find($visit_id);

        if (!$visit) {
            $notifications = array('message' => 'Visit Was Not Found','alert-type'=>'error');
            return redirect()->back()->with($notifications);
        }
        // return $visit;

        return view('admin.visitor.companion',['visit' => $visit]);
    }

    public
    function sendSms($visitingDetail_id)
    {
        $visit_details = VisitingDetails::query()->with('visitor')->find($visitingDetail_id);
        if (empty($visit_details->visitor->phone)) {
            $notifications = array('error' => 'يرجي إدخال رقم هاتف الزائر');
            return redirect()->back()->with($notifications);
        }
        try {
            $job = BackgroundJob::dispatch($visit_details);
        } catch (\Exception) {
            $notifications = array('error' => 'Something Went Wrong');
            return redirect()->back()->with($notifications);
        }
        $notifications = array('success' => __('جاري إرسال الرسالة والإيميل'));
        $visit_details->sent_sms_before = 1;
        $visit_details->save();
        return redirect()->back()->with($notifications);




    }

    public function visitFirstApprove($approval_status)
    {
        $approval_status = decrypt($approval_status);
    }

    public function visitSecondApprove($approval_status)
    {
        $approval_status = decrypt($approval_status);
    }

    public function visitApprove($visit_id)
    {

        $visit_id = decrypt($visit_id);
        $visit = VisitingDetails::query()->with('visitor')->find($visit_id);

        if (!$visit) {
            $notifications = array('message' => 'Visit was not found', 'alert-type' => 'error');
            return redirect()->back()->with($notifications);
        }

        $approval_status = $visit->approval_status;
        if ($approval_status == 0) {
            if ($visit->creatorEmployee->level == 1) {
                $visit->approval_status = 1;
                $visit->save();
                // $this->smsFromApproval($visit);
                $job = BackgroundJob::dispatch($visit);
            }
            if ($visit->creatorEmployee->level == 2) {
                $visit->approval_status = 1;
                $visit->save();
            }
        }

        if ($approval_status == 1) {
            $visit->approval_status = 2;
            $visit->save();
            // $this->smsFromApproval($visit);
            $job = BackgroundJob::dispatch($visit);
        }

        $notifications = array('message' => 'Success Transaction', 'alert-type' => 'success');
        return redirect()->back()->with($notifications);

    }

    public function smsFromApproval($visit)
    {
        try {
            $send_mail = Http::get('https://qudratech-eg.net/mail/tt.php?vid=' . $visit->visitor->id);
            $send_sms = Http::get('https://www.qudratech-eg.net/sms_api.php?mob=' . $visit->visitor->phone);
        } catch (\Exception $e) {
            $notifications = array('message' => 'Visit Approved but Something Went Wrong With Sending Message', 'alert-type' => 'Something Went Wrong');
            return redirect()->back()->with($notifications);
        }
    }

    public
    function play(string $locale)
    {
        return auth()->user()->employee();
    }
}
