<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        
        
        if (Auth::attempt($credentials)) {
            
            return redirect()->route('dashboard');
        } else {
            
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }
}
