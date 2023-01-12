<?php

namespace App\Http\Services\Booking;

use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Employee;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BookingService
{

    public function all(Request $request, $limit = 5): LengthAwarePaginator
    {
        $keyword = $request->get('search');

        if (!empty($keyword)) {
            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('reception')) {
                $results = Booking::query()->latest()
                    ->where('reg_no', 'LIKE', "%$keyword%")
                    ->paginate($limit);
            } else {
                $results = Booking::query()->latest()
                    ->where('user_id', auth()->id())
                    ->where('reg_no', 'LIKE', "%$keyword%")
                    ->paginate($limit);
            }
        } else {
            if (auth()->user()->hasRole('employee')) {
                $results = Booking::query()->latest()
                    ->where('user_id', auth()->id())
                    ->paginate($limit);
            } else {
                $results = Booking::query()->latest()->paginate($limit);
            }
        }

        return $results;
    }


    public function find($id): Model|Collection|Builder|array|null
    {
        return Booking::query()->findorFail($id);
    }


    public function findWhere($column, $value)
    {
        return Booking::query()->where($column, $value)->get();
    }


    public function findWhereFirst($column, $value)
    {
        return Booking::query()->where($column, $value)->first();
    }


    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return Booking::query()->paginate($perPage);
    }



    public function delete($id)
    {
        return Booking::query()->find($id)->delete();
    }

}
