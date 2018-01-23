<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getSignUp()
    {
        return view('user.sign-up');
    }

    public function postSignUp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'name' => 'required|max:120',
            'password' => 'required|min:4'
        ]);
        $email = $request['email'];
        $name = $request['name'];
        $password = bcrypt($request['password']);
        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = $password;
        $user->save();
        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    public function getLogin()
    {
        return view('user.login');
    }
    
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']]))
        {
            return redirect()->route('user.dashboard');
        }
        return redirect()->back();
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function getAccount()
    {
        return view('user.account', ['user' => Auth::user()]);
    }

    public function postSaveAccount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:120'
        ]);
        $imageName = $request->file('image')->getClientOriginalName();
        if(!empty($imageName))
        {
            $extesion1 = substr($imageName, -4);
            $extesion2 = substr($imageName, -5);
            if($extesion1 == '.jpg' || $extesion1 == '.JPG' || $extesion2 == '.jpeg' || $extesion2 == '.JPEG')
            {
                $user = Auth::user();
                $old_name = $user->name;
                $user->name = $request['name'];
                $user->update();
                $file = $request->file('image');
                $filename = $request['name'] . '-' . $user->id . '.jpg';
                $old_filename = $old_name . '-' . $user->id . '.jpg';
                $update = false;
                if (Storage::disk('local')->has($old_filename))
                {
                    $old_file = Storage::disk('local')->get($old_filename);
                    Storage::disk('local')->put($filename, $old_file);
                    $update = true;
                }
                if ($file)
                {
                    Storage::disk('local')->put($filename, File::get($file));
                }
                if ($update && $old_filename !== $filename)
                {
                    Storage::delete($old_filename);
                }
                return redirect()->route('user.account');
            }
            else
            {
                return redirect()->route('user.account')->with('message-error' ,'The image extension must by .jpg only ');
            }
        }
        else
        {
            return redirect()->route('user.account')->with('message-error' ,'enter all fields');
        }

    }

    public function getUserImage($filename)
    {
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }
}