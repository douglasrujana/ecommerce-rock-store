<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    //
    public function showRequestForm()
    {
        return view('auth.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        try {
            Mail::send(
                'emails.reset-password',
                ['token' => $token],
                function ($message) use ($request) {
                    $message->to($request->email)->subject('Recuperación de contraseña');
                }
            );
        } catch (\Exception $e) {
            Log::error('Error enviando correo de recuperación: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Error al enviar el correo. Intenta más tarde.']);
        }

        return back()->with('status', 'Te hemos enviado un enlace de recuperación');
    }

    public function showResetForm($token)
    {
        return view('auth.reset', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if (!$reset || $reset->email !== $request->email) {
            return back()->withErrors(['email' => 'Token invalido o expirado']);
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->input('password'))]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)->delete();

        return redirect()->route('login')->with('mensaje', 'Tu contraseña ha sido restablecida');
    }
}
