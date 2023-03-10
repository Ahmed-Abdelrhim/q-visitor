<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'types';
    protected $guarded = [];
    protected $fillable = [
        'id','name','status','role_one' ,'level', 'role_two','created_at','updated_at'
    ];

    protected $hidden = ['created_at','updated_at'];
    protected $fakeColumns = [];

    public function visitngDetails()
    {
        return $this->hasMany(VisitingDetails::class);
    }



    public $timestamps = false;
}
