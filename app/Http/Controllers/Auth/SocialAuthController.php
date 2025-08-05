<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

/**
 * Controlador para autenticación social (Google, Facebook, etc.)
 * Implementa OAuth usando Laravel Socialite
 */
class SocialAuthController extends Controller
{
    /**
     * Redirigir al proveedor OAuth
     */
    public function redirectToProvider(string $provider)
    {
        $this->validateProvider($provider);
        
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Manejar callback del proveedor OAuth
     */
    public function handleProviderCallback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = $this->findOrCreateUser($socialUser, $provider);
            
            Auth::login($user, true);
            
            return redirect()->intended(route('dashboard'))
                           ->with('mensaje', "¡Bienvenido! Has iniciado sesión con {$provider}.");
                           
        } catch (\Exception $e) {
            return redirect()->route('login')
                           ->withErrors(['social' => 'Error al autenticar con ' . ucfirst($provider) . '. Intenta nuevamente.']);
        }
    }

    /**
     * Buscar o crear usuario desde datos sociales
     */
    private function findOrCreateUser($socialUser, string $provider): User
    {
        $providerIdField = $provider . '_id';
        
        // Buscar por ID del proveedor
        $user = User::where($providerIdField, $socialUser->getId())->first();
        
        if ($user) {
            // Actualizar avatar si cambió
            $this->updateUserAvatar($user, $socialUser);
            return $user;
        }
        
        // Buscar por email
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // Vincular cuenta existente con proveedor social
            $user->update([
                $providerIdField => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
            return $user;
        }
        
        // Crear nuevo usuario
        return User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Usuario',
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(32)), // Password aleatorio
            $providerIdField => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'email_verified_at' => now(),
            'activo' => 1,
        ]);
    }

    /**
     * Actualizar avatar del usuario
     */
    private function updateUserAvatar(User $user, $socialUser): void
    {
        if ($socialUser->getAvatar() && $user->avatar !== $socialUser->getAvatar()) {
            $user->update(['avatar' => $socialUser->getAvatar()]);
        }
    }

    /**
     * Validar que el proveedor sea soportado
     */
    private function validateProvider(string $provider): void
    {
        $supportedProviders = ['google', 'facebook'];
        
        if (!in_array($provider, $supportedProviders)) {
            abort(404, 'Proveedor no soportado');
        }
    }
}