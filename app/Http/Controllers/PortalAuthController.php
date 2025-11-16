<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalAuthController extends Controller
{
    public function create(string $portal)
    {
        $config = $this->portalConfig($portal);

        if (Auth::check()) {
            $role = Auth::user()->role ?? 'admin';
            return redirect()->route($this->redirectRoute($role));
        }

        return view('auth.login', [
            'portal' => $portal,
            'portalConfig' => $config,
        ]);
    }

    public function store(Request $request, string $portal)
    {
        $config = $this->portalConfig($portal);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (
            Auth::attempt([
                ...$credentials,
                'role' => $portal,
            ], $remember)
        ) {
            $request->session()->regenerate();

            return redirect()->intended(route($this->redirectRoute($portal)))
                ->with('status', "{$config['label']} logged in successfully.");
        }

        return back()
            ->withErrors(['email' => 'Invalid credentials for the ' . strtolower($config['label']) . ' portal.'])
            ->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login.admin');
    }

    protected function portalConfig(string $portal): array
    {
        $portals = config('hms.portals');

        return $portals[$portal] ?? abort(404);
    }

    protected function redirectRoute(string $portal): string
    {
        return match ($portal) {
            'patient' => 'patient.portal.dashboard',
            default => 'dashboard',
        };
    }
}
