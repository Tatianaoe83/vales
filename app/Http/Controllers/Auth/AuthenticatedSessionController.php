<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Autenticamos al usuario
        $request->authenticate();

        // 2. Regeneramos la sesión por seguridad
        $request->session()->regenerate();

        // 3. Obtenemos el usuario que acaba de iniciar sesión
        $user = $request->user();

        // 4. Evaluamos los roles y redirigimos a sus paneles correspondientes
        if ($user->hasRole('administrador')) {
            return redirect()->intended('/dashboard'); // El admin va al dashboard principal
        } 
        
        if ($user->hasRole('ventas')) {
            return redirect()->intended('/sales'); // Ventas va directo a su listado de ventas
        } 
        
        if ($user->hasRole('caseta')) {
            return redirect()->intended('/scanner'); // Caseta va directo al escáner
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}