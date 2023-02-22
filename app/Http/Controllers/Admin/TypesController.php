<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\TypesRequest;
use App\Models\Types;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class TypesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->data['sitetitle'] = 'Types';

        $this->middleware(['permission:types'])->only('index');
        $this->middleware(['permission:types_create'])->only('create', 'store');
        $this->middleware(['permission:types_edit'])->only('edit', 'update');
        $this->middleware(['permission:types_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|\Illuminate\View\View
     */
    public function index(): Factory|\Illuminate\View\View
    {
        return view('admin.types.index', $this->data);
    }

    public function create(): Factory|View|Application
    {
        $this->data['roles'] = Role::query()->get(['id', 'name']);
        return view('admin.types.create', $this->data);
    }

    public function store(TypesRequest $request): RedirectResponse
    {
        $role_two = null;
        if (!empty($request->get('role_two')) && $request->get('role_two') != 0) {
            $role_two = $request->get('role_two');
        }

        try {
            DB::beginTransaction();
            Types::query()->insert([
                'name' => $request->get('name'),
                'level' => $request->get('level'),
                'role_one' => $request->get('role_one'),
                'role_two' => $role_two,
                'status' => $request->get('status'),
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $notifications = array('message' => 'Something Went Wrong', 'alert-type' => 'error');
            return redirect()->back()->with($notifications);
        }

        $notifications = array('message' => 'Type created successfully', 'alert-type' => 'success');
        return redirect()->route('admin.types.index')->with($notifications);

        // Types::create($input);

    }

    public function edit($id): Factory|View|Application
    {
        $this->data['types'] = Types::query()->findOrFail($id);
        $this->data['roles'] = Role::query()->get(['id', 'name']);
        return view('admin.types.edit', $this->data);

    }


    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required|string|max:255|unique:types,name,' . $id]);
        $input = $request->all();
        $designation = Types::query()->findOrFail($id);
        $designation->update($input);
        return redirect(route('admin.types.index'))->withSuccess('The Data Updated Successfully');
    }

    public function destroy($id)
    {
        Types::query()->findOrFail($id)->delete();
        return redirect(route('admin.types.index'))->withSuccess('The Data Deleted Successfully');
    }

    public function getTypes(Request $request)
    {

        $designations = Types::query()->orderBy('id', 'desc')->get();

        $i = 1;
        $designationArray = [];
        if (!blank($designations)) {
            foreach ($designations as $designation) {
                $designationArray[$i] = $designation;
                $designationArray[$i]['name'] = Str::limit($designation->name, 100);
                $designationArray[$i]['setID'] = $i;
                $i++;
            }
        }
        return Datatables::of($designationArray)
            ->addColumn('action', function ($designation) {
                $retAction = '';

                if (auth()->user()->can('types_edit')) {
                    $retAction .= '<a href="' . route('admin.types.edit', $designation) . '" 
class="btn btn-sm btn-icon float-left btn-primary" data-toggle="tooltip" 
data-placement="top" title="'.__('files.Edit').'"><i class="far fa-edit"></i></a>';
                }

                if (auth()->user()->can('types_delete')) {
                    $retAction .= '<form class="float-left pl-2" action="' . route('admin.types.destroy', $designation) . '" method="POST">' .
                        method_field('DELETE') . csrf_field() . '<button class="btn btn-sm btn-icon btn-danger" 
data-toggle="tooltip" data-placement="top" title="'.__('files.Delete').'"><i class="fa fa-trash"></i></button></form>';
                }
                return $retAction;
            })
            ->editColumn('status', function ($designation) {
                return ($designation->status == 5 ? trans('statuses.' . Status::ACTIVE) : trans('statuses.' . Status::INACTIVE));
            })
            ->editColumn('id', function ($designation) {
                return $designation->setID;
            })
            ->make(true);

    }

}
