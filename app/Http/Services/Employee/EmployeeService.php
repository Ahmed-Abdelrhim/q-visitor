<?php

namespace App\Http\Services\Employee;

use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\VisitingDetails;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class EmployeeService
{
    /**
     * @param Request $request
     * @param int $limit
     * @return mixed
     */
    public function all()
    {
        return Employee::query()->orderBy('id', 'desc')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Employee::findorFail($id);
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhere($column, $value)
    {
        $result = Employee::where($column, $value)->get();

        return $result;
    }

    public function findWhereFirst($column, $value)
    {
        $result = Employee::where($column, $value)->first();

        return $result;
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage = 10)
    {
        return Employee::paginate($perPage);
    }

    /**
     * @param EmployeeRequest $request
     * @return mixed
     */
    public function make(EmployeeRequest $request)
    {
        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['username'] = $this->username($request->input('email'));
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['password'] = Hash::make($request->input('password'));
        $user = User::query()->create($input);
        // $role = Role::find(2);

        $role = Role::query()->find($request->input('role_id'));

        $user->assignRole($role->name);

        if ($request->file('image')) {
            // $media = $user->addMediaFromRequest('image')->toMediaCollection('user');
            $user->addMedia($request->file('image'))->toMediaCollection('user');
        }
        $result = '';
        if ($user) {
            $data['first_name'] = $request->input('first_name');
            $data['last_name'] = $request->input('last_name');
            $data['phone'] = $request->input('phone');
            $data['user_id'] = $user->id;
            $data['gender'] = $request->input('gender');
            $data['department_id'] = $request->input('department_id');
            $data['designation_id'] = $request->input('designation_id');
            $data['date_of_joining'] = $request->input('date_of_joining');


            $about = strip_tags(trim($request->input('about')));
            $about = str_replace('&nbsp;', '', $request->input('about'));
            $about = str_replace('<p>;', '', $request->input('about'));
            $about = str_replace('</p>', '', $request->input('about'));
            $data['about'] = $about;
            // $data['about'] = $request->input('about');

            $data['level'] = $request->input('level');
            $data['emp_one'] = $request->input('emp_one');
            $data['emp_two'] = $request->input('emp_two');
            $data['creator_employee'] = auth()->user()->employee->id;


            $data['status'] = 5;
            $result = Employee::query()->create($data);


        }
        return $result;

    }

    public function update($id, EmployeeUpdateRequest $request)
    {
        $employee = Employee::query()->find($id);

        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['username'] = $this->username($request->input('email'));
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        // $input['role'] = $request->input('role');
        $user = User::query()->find($employee->user_id);
        $user->update($input);
        if ($request->get('role') > 0 && is_numeric($request->get('role'))) {
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            $role = Role::query()->find($request->get('role'));
            $user->assignRole($role);
        }

        if ($request->file('image')) {
            $user->media()->delete();
            $user->addMedia($request->file('image'))->toMediaCollection('user');
        }
        if ($user) {
            $data['first_name'] = $request->input('first_name');
            $data['last_name'] = $request->input('last_name');
            $data['phone'] = $request->input('phone');
            $data['user_id'] = $user->id;
            $data['gender'] = $request->input('gender');
            $data['department_id'] = $request->input('department_id');
            $data['designation_id'] = $request->input('designation_id');
            $data['date_of_joining'] = $request->input('date_of_joining');

            $data['level'] = $request->input('level');


            $about = str_replace('&nbsp;', '', $request->input('about'));
            $about = str_replace('<p>;', '', $request->input('about'));
            $about = str_replace('</p>', '', $request->input('about'));
            $about = trim($about);
            $data['about'] = $about;

            // data['about'] = $request->input('about');


            $data['status'] = 5;
            $data['creator_employee'] = auth()->user()->employee->id;


            $emp_one = NULL;
            $emp_two = NULL;


            if ($request->level == 1) {
                if ($request->emp_one == 0) {
                    $notifications = array('message' => __('files.You Should Choose Employee One'), 'alert-type' => 'error');
                    return redirect()->back()->with($notifications);
                }
                $emp_one = $request->get('emp_one');
            }

            if ($request->level == 2) {
                if ($request->emp_one == 0 || $request->emp_two == 0) {
                    $notifications = array('message' => __('files.You Should Choose Employee One And Two'), 'alert-type' => 'error');
                    return redirect()->back()->with($notifications);
                }

                $emp_one = $request->get('emp_one');
                $emp_two = $request->get('emp_two');
            }


            $data['emp_one'] = $emp_one;
            $data['emp_two'] = $emp_two;


            $visits = VisitingDetails::query()->where('creator_employee', $employee->id)->get(['id', 'emp_one', 'emp_two']);
            foreach ($visits as $visit) {
                $visit->emp_one = $emp_one;
                $visit->emp_two = $emp_two;
                $visit->save();
            }

            $employee->update($data);
        }

        $notifications = array('message' => __('files.Employee Updated Successfully'), 'alert-type' => 'success');
        return redirect(route('admin.employees.index'))->with($notifications);
        // return $employee;
    }

    public function check($id, $request)
    {

        if ($request['status'] == 1) {
            $checkin = new Attendance();
            $checkin->employee_id = $id;
            $checkin->status = $request['status'];
            $checkin->checkin_time = $request['checkin_time'];
            $checkin->date = date('Y-m-d', strtotime($request['date']));
            $checkin->save();
            return $checkin;
        } elseif ($request['status'] == 2) {

            $checkout = Attendance::where(['employee_id' => $id, 'date' => date('Y-m-d')])->first();
            $checkout->status = $request['status'];
            $checkout->checkout_time = $request['checkout_time'];
            $checkout->date = date('Y-m-d', strtotime($request['date']));
            $checkout->save();
            return $checkout;
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Employee::find($id)->delete();
    }

    private function username($email)
    {
        $emails = explode('@', $email);
        return $emails[0] . mt_rand();
    }
}
