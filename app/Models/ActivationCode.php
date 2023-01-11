<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    use HasFactory;

    public $table = 'activation_code';

    protected $fillable = ['code','mac_address','code_mac'];

    protected $hidden = [];

    protected $casts = [
        'checked_before' => 'integer',
    ];

}
