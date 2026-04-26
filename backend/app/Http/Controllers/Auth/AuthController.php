<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\VerificationCodeMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;

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

            // Restore saved cart from DB into session
            if ($user->cart) {
                session(['cart' => $user->cart]);
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

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name'                    => $request->name,
            'email'                   => $request->email,
            'role'                    => 'customer',
            'password'                => Hash::make($request->password),
            'verification_code'       => $code,
            'verification_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        session(['verify_email' => $user->email, 'verify_user_id' => $user->id]);

        return redirect()->route('verify.show');
    }

    public function showVerify()
    {
        if (!session('verify_user_id')) return redirect()->route('register');
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = User::find(session('verify_user_id'));

        if (!$user || $user->verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Mali ang verification code.']);
        }

        if (now()->isAfter($user->verification_expires_at)) {
            return back()->withErrors(['code' => 'Expired na ang code. Magpadala ulit.']);
        }

        $user->update([
            'email_verified_at'       => now(),
            'verification_code'       => null,
            'verification_expires_at' => null,
        ]);

        session()->forget(['verify_email', 'verify_user_id']);
        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }

    public function resendCode()
    {
        $user = User::find(session('verify_user_id'));
        if (!$user) return redirect()->route('register');

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'verification_code'       => $code,
            'verification_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return back()->with('success', 'Bagong code na ang napadala sa iyong email.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'Walang account na may email na ito.',
        ]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password_reset_code'       => $code,
            'password_reset_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new ForgotPasswordMail($code));
        session(['reset_email' => $user->email]);

        return redirect()->route('password.reset.show');
    }

    public function showResetPassword()
    {
        if (!session('reset_email')) return redirect()->route('forgot.password');
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|size:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user || $user->password_reset_code !== $request->code) {
            return back()->withErrors(['code' => 'Mali ang reset code.']);
        }

        if (now()->isAfter($user->password_reset_expires_at)) {
            return back()->withErrors(['code' => 'Expired na ang code. Humiling ulit.']);
        }

        $user->update([
            'password'                  => \Illuminate\Support\Facades\Hash::make($request->password),
            'password_reset_code'       => null,
            'password_reset_expires_at' => null,
        ]);

        session()->forget('reset_email');
        return redirect()->route('login')->with('success', 'Na-reset na ang iyong password. Mag-login na.');
    }

    public function logout(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        // Save cart to DB before clearing session
        if (Auth::check()) {
            Auth::user()->update(['cart' => session('cart', [])]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $isAdmin
            ? redirect()->route('login')
            : redirect()->route('home');
    }
}
