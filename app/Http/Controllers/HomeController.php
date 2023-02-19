<?php

namespace App\Http\Controllers;

use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function changeLocaleLanguage(string $iso)
    {
        $lang = Languages::query()->where('iso',$iso)->first();
        if ($lang) {
            if (session()->has('locale')){
                $locale = Session::get('locale');
                $notifications = array('message' =>'language was changed','alert-type'=>'success');
                return redirect()->back()->with($notifications);
            }
        }
        $notifications = array('message' =>'language was not found','alert-type'=>'info');
        return redirect()->back()->with($notifications);
    }
}
