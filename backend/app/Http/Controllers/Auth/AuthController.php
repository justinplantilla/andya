<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors(['email' => 'Mali ang email o password.'])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'Pakiusap ilagay ang iyong pangalan.',
            'email.required'     => 'Pakiusap ilagay ang iyong email.',
            'email.email'        => 'Hindi valid ang email address.',
            'email.unique'       => 'Ang email na ito ay ginagamit na. Subukan ang ibang email.',
            'password.required'  => 'Pakiusap ilagay ang password.',
            'password.min'       => 'Ang password ay dapat hindi bababa sa 8 characters.',
            'password.confirmed' => 'Hindi magkatugma ang mga password.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'customer',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
