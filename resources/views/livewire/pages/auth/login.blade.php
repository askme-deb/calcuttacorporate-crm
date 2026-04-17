<?php
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;
    public bool $showPassword = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
    }

    public function togglePasswordVisibility(): void
    {
        $this->showPassword = !$this->showPassword;
    }
};
?>

<!-- Session Status -->
<form class="my-4" wire:submit.prevent="login">
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="form-group mb-2">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input wire:model="form.email" id="email" type="email" name="email" required autofocus
            autocomplete="username" />
        @if ($errors->has('form.email'))
            <div class="text-danger small mt-1">
                {{ $errors->first('form.email') }}
            </div>
        @endif

    </div><!--end form-group-->

    <div class="form-group">
        <x-input-label for="password" :value="__('Password')" />
        <div class="position-relative">
            <x-text-input wire:model="form.password" id="password" :type="$showPassword ? 'text' : 'password'" name="password" required
                autocomplete="current-password" class="form-control" />
            <i class="fas fa-{{ $showPassword ? 'eye-slash' : 'eye' }} position-absolute top-50 end-0 translate-middle-y me-3"
                style="cursor: pointer;" wire:click="togglePasswordVisibility"></i>
        </div>
        <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
    </div>

    <div class="form-group row mt-3">
        <div class="col-sm-6">
            <div class="form-check form-switch form-switch-success">
                <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                    name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
        </div><!--end col-->
        <div class="col-sm-6 text-end">
            @if (Route::has('password.request'))
                <a class="text-muted font-13" href="{{ route('password.request') }}" wire:navigate>
                    <i class="dripicons-lock"></i> {{ __('Forgot your password?') }}
                </a>
            @endif
        </div><!--end col-->
    </div><!--end form-group-->

    <div class="form-group mb-0 row">
        <div class="col-12">
            <div class="d-grid mt-3">
                <x-primary-button class="">
                    <span wire:loading wire:target="login">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                    <span wire:loading.remove wire:target="login">
                        Log in <i class="fas fa-sign-in-alt ms-1"></i>
                    </span>
                </x-primary-button>
            </div>
        </div><!--end col-->
    </div> <!--end form-group-->
</form><!--end form-->
