<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On; 
class LockScreen extends Component
{
    public $password = '';
    public $errorMessage = '';

    public function unlock()
    {
        // Validate user password
        if (Auth::attempt(['email' => Auth::user()->email, 'password' => $this->password])) {
            session()->put('locked', false); // Unlock the session
            return redirect()->route('dashboard'); // Redirect to a safe page
        }

        $this->errorMessage = 'Incorrect password.';
        $this->password = '';
    }
    public function mount()
    {
        if (session()->get('locked', false)) {
            session()->put('locked', true);
        }
    }

    protected $listeners = ['lockScreen'];

    public function lockScreen()
    {
        session()->put('locked', true);
        return redirect()->route('lock-screen');
    }

    public function render()
    {
        return view('livewire.pages.auth.lock-screen');
    }
}
