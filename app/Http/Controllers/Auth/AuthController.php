<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Auth;
use Exception;
use Google_Client;
use Google_Service_People;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try
        {
            $user = Socialite::driver('facebook')->user();
            $checkUser = User::where('email', '=', $user->email)->first();
            if ($checkUser !== null)
            {
                Auth::login($checkUser);
                return redirect()->route('user.dashboard');
            }
            else
            {
                $newUser = new User();
                $newUser->email = $user->email;
                $newUser->name = $user->name;
                $newUser->password = '';
                $newUser->facebook_id = $user->id;
                $newUser->google_id = '';
                $newUser->save();
                Auth::login($newUser);
                return redirect()->route('user.dashboard');
            }
        }catch (Exception $e)
        {
            return redirect()->route('user.login');
        }
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes(['profile', 'email'])->redirect();
    }

    public function handleGoogleCallback()
    {
        try
        {
            $user = Socialite::with('google')->stateless()->user();
            $checkUser = User::where('email', '=', $user->email)->first();
            if ($checkUser != null)
            {
                Auth::login($checkUser);
                return redirect()->route('user.dashboard');
            }
            else
            {
                $newUser = new User();
                $newUser->email = $user->email;
                $newUser->name = $user->name;
                $newUser->password = '';
                $newUser->facebook_id = '';
                $newUser->google_id = $user->id;
                $newUser->save();
                Auth::login($newUser);
                return redirect()->route('user.dashboard');
            }
        } catch (Exception $e)
        {
            return redirect()->route('user.login');
        }
    }
}