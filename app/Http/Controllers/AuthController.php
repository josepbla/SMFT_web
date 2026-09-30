<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session()->has('admin_user')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:120'],
            'password' => ['required', 'string'],
        ]);

        $user = DB::table('usuarios')
            ->where('email', $credentials['email'])
            ->where('estado', 'activo')
            ->first();

        $passwordMatches = false;
        if ($user) {
            $passwordInfo = password_get_info($user->password);
            if (($passwordInfo['algo'] ?? 0) === 0) {
                $passwordMatches = hash_equals((string) $user->password, $credentials['password']);
                if ($passwordMatches) {
                    $newHash = Hash::make($credentials['password']);
                    DB::table('usuarios')->where('id_usuario', $user->id_usuario)->update(['password' => $newHash]);
                    $user->password = $newHash;
                }
            } else {
                $passwordMatches = Hash::check($credentials['password'], $user->password);
            }
        }

        if (! $user || ! $passwordMatches) {
            return back()->withErrors(['email' => 'El correo o la contraseña no son correctos.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('admin_user', [
            'id' => $user->id_usuario,
            'nombres' => $user->nombres,
            'email' => $user->email,
            'rol' => $user->rol,
        ]);

        DB::table('usuarios')->where('id_usuario', $user->id_usuario)->update(['ultimo_acceso' => now()]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_user');
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Sesión cerrada correctamente.');
    }
}