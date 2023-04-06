<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthenticateFile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $rand = round(0 + mt_rand() / mt_getrandmax() * (1.0 - 0), 2);

        // Read the user credentials from the text file
        $contents = Storage::disk('public')->get('login.txt');;
        $users = explode(",", $contents);

        if ($username === $users[0] && $password === $users[1]) {
            // Credentials match, store the user details in the session
            session(['login' => $username . $rand . $password]);
            // return $next($request);
            return redirect()->intended('/')->with('message', 'Berhasil masuk sistem');
        }

        // Credentials do not match, redirect to the login page
        return back()->with('error', 'Invalid credentials.');
    }
}