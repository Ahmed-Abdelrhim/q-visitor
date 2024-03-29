<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Shipu\Watchable\Traits\HasAuditColumn;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Traits\HasRoles;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Employee extends Model implements  HasMedia
{
    use InteractsWithMedia;
    use HasAuditColumn;
    use HasRoles;



    protected $table = 'employees';
    protected $guarded = ['id'];
    protected $auditColumn = true;

    protected $fakeColumns = [];

    public function creator()
    {
        return $this->morphTo();
    }

    public function editor()
    {
        return $this->morphTo();
    }

    public function visitngDetails()
    {
        return $this->hasMany(VisitingDetails::class,'creator_id','id');
    }

    public function visit()
    {
        return $this->hasMany(VisitingDetails::class,'user_id','id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class,'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getMyStatusAttribute()
    {
        return trans('statuses.' . $this->status);
    }
    public function getMyGenderAttribute()
    {
        return trans('genders.' . $this->gender);
    }

}
