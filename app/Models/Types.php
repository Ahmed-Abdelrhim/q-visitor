<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'types';
    protected $guarded = ['id'];
    protected $fillable = [
        'name','status' , 'role_one' , 'role_two','created_at','updated_at'
    ];

    protected $hidden = ['created_at','updated_at'];
    protected $fakeColumns = [];

    public $timestamps = false;
}
