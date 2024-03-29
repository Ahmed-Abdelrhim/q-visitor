<?php

namespace App\Models;

use App\Http\Controllers\Admin\WorkerController;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Shipu\Watchable\Traits\HasAuditColumn;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class VisitingDetails extends Model implements  HasMedia
{
    use InteractsWithMedia;
    use HasAuditColumn;

    protected $table = 'visiting_details';
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


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function visitor()
    {
        return $this->belongsTo(Visitor::class,'visitor_id');
    }

    public function owner()
    {
        return $this->belongsTo(Employee::class,'user_id','id');
    }

public function empvisit()
{
    return $this->belongsTo(Employee::class,'user_id');
}


public function employee()
    {
//        return $this->belongsTo(Employee::class, 'employee_id');
        return $this->belongsTo(Employee::class, 'creator_employee');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function creatorEmployee()
    {
        return $this->belongsTo(Employee::class,'creator_employee','id');
    }

    public function type()
    {
        return $this->belongsTo(Types::class,'type_id');
    }

    public function companions()
    {
        return $this->hasMany(Companion::class,'visit_id','id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class,'shipment_id','id');
    }

    public function getMyStatusAttribute()
    {
        return trans('statuses.' . $this->status);
    }

    public function registerMediaCollections(): void
    {
        // TODO: Implement registerMediaCollections() method.
        $this->addMediaCollection('visitor')
            ->singleFile();
    }

    public function getImagesAttribute()
    {
        if (!empty($this->getFirstMediaUrl('visitor'))) {
            return asset($this->getFirstMediaUrl('visitor'));
        }
        return asset('assets/img/default/user.png');
    }

    public function workers()
    {
        return $this->hasMany(Worker::class , 'visit_id', 'id');
    }

    // Deleting Every Thing Related To Visit When Visit Is Deleted
    protected static function booted () {
        static::deleting(function(VisitingDetails $visit) {
            // before delete() method call this
            $visit?->workers()->delete();
            $visit?->visitor()->delete();
            $visit?->companions()->delete();
            // do the rest of the cleanup...
        });
    }

}

