<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    public $table = 'shipments';
    protected $guarded = [];
    public $hidden = [];

//    public function visit()
//    {
//            return $this->belongsTo(VisitingDetails::class,'shipment_id');
//    }
}
