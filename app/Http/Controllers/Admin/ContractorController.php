<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    public function index($contractor_id)
    {
        $contractor_id = decrypt($contractor_id);
        return view('admin.visitor.contractor',['contractor_id' => $contractor_id]);
    }

    public function store(Request $request , $contractor_id)
    {
        return $request;
    }
}
