<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    public function index($contractor_id)
    {
        $contractor_id = decrypt($contractor_id);
        return view('admin.visitor.contractor', ['contractor_id' => $contractor_id]);
    }

    public function store(Request $request, $contractor_id)
    {
        $request = $request->except('_token');
        $name = [];
        $nat = [];
        $length = count($request) / 2;
        for ($i = 1; $i <= $length; $i++) {
            if ($i == 1) {
                $name[] .= $request['name'];
                $nat[] .= $request['nat'];
            } else {
                $counter = $i;
                $name_index = 'name' . $counter;
                $nat_index = 'nat' . $counter;
                $name[] .= $request[$name_index];
                $nat[] .= $request[$nat_index];
            }
        }
        return $nat;
    }
}


// Table Workers
// big-int : id , string: name , big-int: nat_id , int: visit_id , int: visitor_id , timestamp: created_at , timestamp: updated_at