<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends BackendController
{
    public $notDeleteArray = [1, 2, 3 , 14 , 15];

    public function __construct()
    {
        parent::__construct();
        $this->data['sitetitle']      = 'Roles';
        $this->data['notDeleteArray'] = $this->notDeleteArray;

        $this->middleware(['permission:role'])->only('index');
        $this->middleware(['permission:role_create'])->only('create', 'store');
        $this->middleware(['permission:role_edit'])->only('edit', 'update');
        $this->middleware(['permission:role_delete'])->only('destroy');
        $this->middleware(['permission:role_show'])->only('show', 'savePermission');

    }

    public function index()
    {
        $this->data['roles'] = Role::query()->orderBy('id','asc')->get();
        return view('admin.role.index', $this->data);
    }


    public function create()
    {
        return view('admin.role.create', $this->data);
    }

    public function store(RoleRequest $request)
    {
        $role       = new Role;
        $role->name = $request->name;
        $role->save();

        return redirect(route('admin.role.index'))->withSuccess('The Data Inserted Successfully');
    }


    public function show($id)
    {
        $role = Role::query()->findOrFail($id);

        $listPermissionsArray = [];
        $permissions          = Permission::query()->get();
        // return strpos($permissions[0]->name, '_create');
        // return $permissions[0]; // name => dashboard
        if (count($permissions)) {
            foreach ($permissions as $permission) {
                if ((strpos($permission->name, '_create') == false) && (strpos($permission->name, '_edit') == false) && (strpos($permission->name, '_show') == false) && (strpos($permission->name, '_delete') == false)) {
                    $listPermissionsArray[$permission->id] = $permission;
                }
                $permissionArray[$permission->name] = $permission->id;
            }
        }

        // return $listPermissionsArray;

        $this->data['role']            = $role;
        $this->data['permissions']     = $role->permissions->pluck('id', 'id');
        $this->data['permissionArray'] = $permissionArray;
        $this->data['permissionList']  = $listPermissionsArray;
        return view('admin.role.show', $this->data);
    }


    public function edit($id)
    {
        $this->data['role'] = Role::query()->findOrFail($id);
        return view('admin.role.edit', $this->data);
    }


    public function update(RoleRequest $request, $id)
    {
        $role       = Role::query()->findOrFail($id);
        $role->name = $request->name;
        $role->save();
        return redirect(route('admin.role.index'))->withSuccess('The Data Updated Successfully');
    }

    public function destroy($id)
    {
        if (in_array($id, $this->notDeleteArray)) {
            return redirect(route('admin.role.index'))->withError('The Data Not Deleted Successfully');
        } else {
            Role::query()->findOrFail($id)->delete();
            return redirect(route('admin.role.index'))->withSuccess('The Data Deleted Successfully');
        }
    }

    public function savePermission(Request $request, $id)
    {
        if ($_POST) {
            $permissions = $request->all();
            unset($permissions['_token']);

            if (!$request->has('dashboard')) {
                $request->merge(['dashboard' => '1']);
            }
            $permissions = array_values($permissions);

            $role       = Role::find($id);
            $permission = Permission::whereIn('id', $permissions)->get();
            $role->syncPermissions($permission);

            return redirect(route('admin.role.show', $role))->withSuccess('The Permission Updated Successfully');
        }
        return redirect(route('admin.role.index'));
    }
}
