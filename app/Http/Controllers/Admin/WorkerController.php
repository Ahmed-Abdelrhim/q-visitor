<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
    }

    public function search(Request $request)
    {
        $visit_id = $request->contractor;
        $workers = Worker::query()
            ->with('visit')
            ->with('visitor')
            ->where('visit_id', $visit_id)
            ->get();
        return $workers;


    }
}
