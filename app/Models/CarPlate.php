<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPlate extends Model
{
    use HasFactory;
    protected $table = 'car_plates';
    protected $fillable = ['plate_number' , 'flag' ,'created_at' , 'updated_at'];

}
