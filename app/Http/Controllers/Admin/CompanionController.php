<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanionController extends Controller
{
    public function index($id)
    {
        $id = decrypt($id);

    }



}
