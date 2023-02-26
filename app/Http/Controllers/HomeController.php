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
        $this->middleware('auth')->only('index');
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

    public function changeLocaleLanguage(string $locale)
    {
        $lang = Languages::query()->where('iso', '=', $locale)->first();

        if ($lang) {
            $locale = session()->put('locale', $locale);
            $notifications = array('message' => __('files.language was changed'), 'alert-type' => 'success');
            return redirect()->back()->with($notifications);
        }
        $notifications = array('message' => __('files.language was not found'), 'alert-type' => 'info');
        return redirect()->back()->with($notifications);
    }
}
