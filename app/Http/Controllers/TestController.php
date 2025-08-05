<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Controlador para endpoints de testing E2E
 * Solo disponible en entorno de testing
 */
class TestController extends Controller
{
    public function __construct()
    {
        // Solo permitir en entorno de testing
        if (!app()->environment('testing')) {
            abort(404);
        }
    }

    public function createUser(Request $request)
    {
        $userData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'activo' => 1,
        ]);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function getResetToken(Request $request)
    {
        $email = $request->validate(['email' => 'required|email'])['email'];
        
        $token = DB::table('password_reset_tokens')
                   ->where('email', $email)
                   ->first();

        return response()->json([
            'token' => $token ? $token->token : null
        ]);
    }

    public function createResetToken(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string'
        ]);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $data['email']],
            ['token' => $data['token'], 'created_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function cleanupUser(Request $request)
    {
        $email = $request->validate(['email' => 'required|email'])['email'];
        
        User::where('email', $email)->delete();
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json(['success' => true]);
    }
}