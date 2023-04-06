<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $rand = round(0 + mt_rand() / mt_getrandmax() * (1.0 - 0), 2);

        // Read the user credentials from the text file
        $contents = Storage::disk('public')->get('login.txt');
        $users = explode(",", $contents);

        if ($username === $users[0] && $password === $users[1]) {
            // Credentials match, store the user details in the session
            // $login = [
            //     'username' => $username,
            //     'password' => $password
            // ];
            // auth()->loginUsingId($users[0]);
            session(['login' => $username . $rand . $password]);
            // return $next($request);
            return redirect()->intended('/')->with('message', 'Berhasil masuk sistem');
            // dd(auth()->check());
        }

        // if (Auth::login($login)) {
        //     session(['login' => $username . $rand . $password]);
        //     // return $next($request);
        //     return redirect()->intended('/')->with('message', 'Berhasil masuk sistem');
        // }

        // Credentials do not match, redirect to the login page
        return back()->with('error', 'Invalid credentials.');
    }

    public function logout(Request $request)
    {

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('message', 'Berhasil keluar sistem');
    }
}