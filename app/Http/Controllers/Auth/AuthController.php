<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * 1. Login Tradicional (Funcionarios / Administradores)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([
            'correo' => $credentials['correo'],
            'password' => $credentials['password'],
            'activo' => true])) {
            $request->session()->regenerate();
            
            // Redirección temporal solicitada hacia la ruta o vista de baches
            return redirect()->to('/baches'); 
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros o la cuenta está inactiva.',
        ]);
    }

    public function handleGoogleCallback()
    {
        try {
            // Opción recomendada para evitar fallos de estado/sesión en móviles: stateless()
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['correo' => $googleUser->getEmail()],
                [
                    'nombre' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'rol_id' => 1, // Ciudadano por defecto
                    'password' => null,
                    'activo' => true,
                ]
            );

            auth()->login($user);

            return redirect()->intended('/baches');

        } catch (InvalidStateException $e) {
            // Si la sesión caduca o hay desajuste de estado, lo mandamos al login con un mensaje limpio
            return redirect()->route('login')->with('error', 'La sesión de autenticación expiró. Por favor, intenta iniciar sesión de nuevo.');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Ocurrió un error al intentar iniciar sesión con Google.');
        }
    }

    /**
     * 2. Redirección hacia Google (Ciudadanos)
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    /**
     * 4. Cerrar Sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
}