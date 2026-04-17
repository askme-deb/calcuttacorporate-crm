<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit.prevent="sendPasswordResetLink" class="my-4">
        <!-- Email Address -->
        <div class="form-group mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div><!--end form-group-->

        <div class="form-group mb-0 row">
            <div class="col-12">
                <x-primary-button class="w-100">
                    {{-- {{ __('Email Password Reset Link') }} <i class="fas fa-sign-in-alt ms-1"></i> --}}
                    <span wire:loading wire:target="sendPasswordResetLink">
                        Email Password Reset Link <i class="fa fa-spinner fa-spin"></i>
                     </span>
                     <span wire:loading.remove wire:target="sendPasswordResetLink">
                        Email Password Reset Link <i class="fas fa-sign-in-alt ms-1"></i>
                     </span>
                </x-primary-button>
            </div><!--end col-->
        </div> <!--end form-group-->
    </form>
    <div class="text-center text-muted">
        <p class="mb-1">Remember It ?  <a href="{{ route('login') }}" wire:navigate class="text-primary ms-2">Sign in here</a></p>
    </div>

</div>
