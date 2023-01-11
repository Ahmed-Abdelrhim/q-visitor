<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class LoginController extends Controller
{
    const SALT_CODE = 'BxT6;<cXWgR9j\PVp;PA0*np-UIc7"XM;HL>JG/';
    const SALT_MAC = 'D<QE0vdlILA\cHP;OF*Z;TY6l/*KGO"D0>v';
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(): Factory|View|Application
    {
        // TODO::Check If The Table Activation Code Has Ans Rows
        // activation.code_check
        $activation = ActivationCode::query()->count();
        if ($activation >= 1) {
            $mac_address = substr(exec('getmac'), 0, 17);
            $codeRow = ActivationCode::query()->latest()->first();
            if (Hash::check($mac_address, $codeRow->mac_address)) {
                if ($codeRow->checked_before != 1 )
                    return view('activation.code_check');
                return view('admin.auth.login');
            }

            return view('errors.404',['msg' => 'Content Is Blocked']);
        }
        return view('activation.code_check');
    }

    public function codeActivation(Request $request): Redirector|RedirectResponse|Application
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $activation = ActivationCode::query()->latest()->first();
        if (!$activation) {
            session()->flash('error','There Is No Codes To Check , You Can Call Qudra-tech for Activation Codes');
            return redirect()->back();
        }

        $current_mac_address = substr(exec('getmac'), 0, 17);
        $code = $request->get('code');

        $hashed_mac_address = str_replace(self::SALT_MAC,'',$activation->mac_address);
        $hashed_code = str_replace(self::SALT_CODE,'',$activation->code);

        if (Hash::check($current_mac_address, $hashed_mac_address)) {
            if (Hash::check($code, $hashed_code)) {
                $activation->checked_before = 1;
                $activation->save();
                return redirect('login');
            }
            session()->flash('error', 'Code Is Wrong');
            return redirect()->back();
        }
        session()->flash('mac', 'Mac Address Is Not The Same');
        return redirect()->back();
    }

    public function insertSomeCodes(): RedirectResponse
    {
        $mac_address = substr(exec('getmac'), 0, 17);
        // F0-D5-BF-DA-DF-D5
        // $code = Str::random(8);
        $salt = '';

        $code = '12345678';
        $code_mac = $code . $mac_address;
        return $this->insertion($code,$mac_address,$code_mac);
    }

    public function insertion($code,$mac_address,$code_mac): RedirectResponse
    {
        $salt_code = self::SALT_CODE;
        $hashed_code = Hash::make($code) .$salt_code;
        $salt_mac = self::SALT_MAC;
        $hashed_mac = Hash::make($mac_address) .$salt_mac;
        try {
            DB::beginTransaction();
            ActivationCode::query()->create([
                'code' => $hashed_code,
                'mac_address' => $hashed_mac,
                'code_mac' => Hash::make($code_mac),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            session()->flash('error', 'Something Went Wrong While Inserting Code');
            return redirect()->back();
        }
        DB::commit();
        session()->flash('success', 'Code Inserted Successfully');
        return redirect()->route('code.activation.page');
    }


    public function showCodeActivationPage(): Factory|View|Application
    {
        return view('activation.code_check');
    }

    public function session(): string
    {
        // session()->forget('success');
        //        $salt = 'BxT6;<cXWgR9j\PVpPA0*np-UIc7XMHL>JG/';
        //        return strlen($salt);
        return Str::random(25);
        //        if (Session::has('success'))
        //            return 'Yes';
        //        return 'No';
    }

    protected function attemptLogin(Request $request): bool
    {

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

//    protected function authenticated(Request $request, $user)
//    {
//        return redirect(route('dashboard.index'));
//    }

public function logout(): RedirectResponse
{
    Auth::logout();
    return redirect()->route('login');
}




}

// admin@example.com  id => 1
// demo@example.com => id = 9

// Admin  => id = 1 ,  Employee => id = 2  , Reception  => id = 3 , Delivery1  => id = 4  ,  HR  => id = 6

