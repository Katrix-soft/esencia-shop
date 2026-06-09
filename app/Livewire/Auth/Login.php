<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

#[Title('Acceso | Esencia')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    // Registro
    public $name = '';
    public $password_confirmation = '';

    public $isRegistering = false;

    public function toggleMode()
    {
        $this->isRegistering = !$this->isRegistering;
        $this->resetValidation();
        $this->reset(['password', 'password_confirmation', 'name']);
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo es inválido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->flash('auth_success', '¡Sesión iniciada con éxito! Redirigiendo...');
            
            $this->dispatch('auth-success');

            $intendedUrl = session()->pull('url.intended', route('catalog'));
            return redirect()->to($intendedUrl);
        }

        session()->flash('auth_error', 'Las credenciales proporcionadas no coinciden con nuestros registros.');
        $this->addError('email', 'Correo o contraseña incorrectos.');
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo es inválido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        Auth::login($user);

        session()->flash('auth_success', '¡Cuenta creada con éxito! Redirigiendo a tu pedido...');
        
        $this->dispatch('auth-success');

        $intendedUrl = session()->pull('url.intended', route('catalog'));
        return redirect()->to($intendedUrl);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
