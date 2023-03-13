<?php

namespace App\Http\Services\PreRegister;

use App\Enums\Status;
use App\Http\Requests\PreRegisterRequest;
use App\Models\PreRegister;
use App\Models\Visitor;
use App\Notifications\SendInvitationToVisitors;
use App\Notifications\SendVisitorToEmployee;
use Illuminate\Http\Request;
use DB;

class PreRegisterService
{

    public function all()
    {
        if(auth()->user()->getrole->name == 'Employee') {
            return PreRegister::query()->where(['employee_id'=>auth()->user()->employee->id])

                ->orderBy('id', 'desc')->get();
        }else {
            return PreRegister::query()->orderBy('id', 'desc')->get();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if(auth()->user()->getrole->name == 'Employee') {
            return PreRegister::query()->where(['id'=>$id,'employee_id'=>auth()->user()->employee->id])->first();
        }else {
            return PreRegister::query()->find($id);
        }
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhere($column, $value)
    {
        $result = PreRegister::query()->where($column, $value)->get();

        return $result;
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhereFirst($column, $value)
    {
        $result = PreRegister::query()->where($column, $value)->first();

        return $result;
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage = 10)
    {
        return PreRegister::paginate($perPage);
    }

    /**
     * @param PreRegisterRequest $request
     * @return mixed
     */
    public function make(PreRegisterRequest $request)
    {
        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['gender'] = $request->input('gender');

        $address = strip_tags(trim($request->input('address')));
        $address = str_replace('&nbsp;' , '' , $request->input('address'));
        $address = str_replace('<p>;' , '' , $request->input('address'));
        $address = str_replace('</p>;' , '' , $request->input('address'));
        $input['address'] = $address;


        $input['is_pre_register'] = true;
        $input['status'] = Status::ACTIVE;
        $visitor = Visitor::query()->create($input);
        $result='';
        if($visitor) {
            $preArray['expected_date']  = $request->input('expected_date');
            $preArray['expected_time']  = date('H:i:s', strtotime($request->input('expected_time')));
            $preArray['exit_date']  = $request->input('exit_date');
            $preArray['exit_time']  = date('H:i:s', strtotime($request->input('exit_time')));

            $comment = strip_tags(trim($request->input('comment')));
            $comment = str_replace('&nbsp;','',$request->input('comment'));
            $comment = str_replace('<p>;','',$request->input('comment'));
            $comment = str_replace('</p>','',$request->input('comment'));
            $preArray['comment'] = $comment;


            $preArray['visitor_id']     = $visitor->id;
            $preArray['employee_id']     = $request->input('employee_id');
            $result = PreRegister::query()->create($preArray);
            try {
                $result->visitor->user()->notify(new SendInvitationToVisitors($result));
            } catch (\Exception $e) {
                // Using a generic exception
            }
        }
        return $result;

    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $pre_register = PreRegister::query()->findOrFail($id);

        $input['first_name'] = $request->input('first_name');
        $input['last_name'] = $request->input('last_name');
        $input['email'] = $request->input('email');
        $input['phone'] = $request->input('phone');
        $input['gender'] = $request->input('gender');

        $address = strip_tags(trim($request->input('address')));
        $address = str_replace('&nbsp;', '', $address);
        $address = str_replace('<p>;', '', $address);
        $address = str_replace('</p>;', '', $address);
        $input['address'] = $address;


        // $input['address'] = $request->input('address');

        // $input['employee_id'] = $request->input('employee_id');
        $input['is_pre_register'] = true;
        $input['status'] = Status::ACTIVE;
        $pre_register->visitor->update($input);
        if($pre_register) {
            $preArray['expected_date']  = $request->input('expected_date');
            $preArray['expected_time']  = date('H:i:s', strtotime($request->input('expected_time')));
            $preArray['exit_date']  = $request->input('exit_date');
            $preArray['exit_time']  = date('H:i:s', strtotime($request->input('exit_time')));


            $comment = strip_tags(trim($request->input('comment')));
            $comment = str_replace('&nbsp;', '', $comment);
            $comment = str_replace('<p>;', '', $comment);
            $comment = str_replace('</p>;', '', $comment);
            $preArray['comment']  = $comment;

            // $preArray['comment']        = $request->input('comment');


            $preArray['employee_id'] = $request->input('employee_id');
            $pre_register->update($preArray);
            try {
                $pre_register->visitor->user()->notify(new SendInvitationToVisitors($pre_register));
            } catch (\Exception $e) {
                // Using a generic exception
            }
        }
        return $pre_register;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return PreRegister::find($id)->delete();
    }

}
