<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username == '' || $password == '') {
            return back()->with('warning', 'Silakan isi username / password!');
        }

        $rand = round(0 + mt_rand() / mt_getrandmax() * (1.0 - 0), 2);

        // Read the user credentials from the text file
        $contents = Storage::disk('public')->get('login.txt');
        $users = explode(",", $contents);

        if ($username === $users[0] && $password === $users[1]) {
            // Credentials match, store the user details in the session
            $arr = ["aktivitas" => ["Login"], "waktu" => [Carbon::now()->format('H:i:m')]];

            session(['login' => $username . $rand . $password]);
            session(["history" => $arr]);

            return redirect()->intended('/')->with('message', Lang::get('notif.login'));
        }

        // if (Auth::login($login)) {
        //     session(['login' => $username . $rand . $password]);
        //     // return $next($request);
        //     return redirect()->intended('/')->with('message', 'Berhasil masuk sistem');
        // }

        // Credentials do not match, redirect to the login page
        return redirect('/login')->with('error', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        $notif = Lang::get('notif.logout');
        Auth::logout();
        // $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('message', $notif);
    }
}
