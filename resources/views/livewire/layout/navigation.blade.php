<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom" id="navbar-custom" >
        <ul class="list-unstyled topbar-nav float-end mb-0" >
        <livewire:notifications />
            <li class="dropdown">
                <div x-data="{ open: $wire.entangle('showDropdown') }">
                    <a href="javascript:;" x-on:click="open = true" class="nav-link nav-user">
                        <div class="d-flex align-items-center">
                            <img src="{{empProfilePicture(auth()->id())}}" alt="profile-user" class="rounded-circle me-2 thumb-sm" />
                            <div>
                                <small class="d-none d-md-block font-11">{{$role = Auth::user()->roles->first()->name ?? 'Unknown User';
                                }}</small>
                                <span class="d-none d-md-block fw-semibold font-12">{{ Auth::user()->name }}<i
                                        class="mdi mdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu-end " style="position: absolute;inset: 0px 0px auto auto;margin: 0px;transform: translate(-8px, 38px);border: 1px solid rgb(236 238 240);display: block;box-shadow: 0 3px 12px rgba(214,228,241,.3);background: #ffffff;color: #000444;width: 100%;" x-show="open" x-on:click.outside="open = false">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            <i class="ti ti-user font-16 me-1 align-text-bottom"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <a class="dropdown-item p-2" href="#"><i class="ti ti-settings font-16 me-1 align-text-bottom"></i> Settings</a>
                        <div class="dropdown-divider mb-0" style="border-top: 1px solid #f2f2f2; opacity: 1;"></div>
                        <x-dropdown-link wire:click="logout" href="javascript:void(0);">
                            <i class="ti ti-power font-16 me-1 align-text-bottom"></i> {{ __('Log Out') }}
                        </x-dropdown-link>
                    </div>
                </div>
            </li>

            {{-- <li class="dropdown" wire:ignore>
                <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <img src="{{empProfilePicture(auth()->id())}}" alt="profile-user" class="rounded-circle me-2 thumb-sm" />
                        <div>
                            <small class="d-none d-md-block font-11">{{$role = Auth::user()->roles->first()->name ?? 'Unknown User';
                            }}</small>
                            <span class="d-none d-md-block fw-semibold font-12">{{ Auth::user()->name }}<i
                                    class="mdi mdi-chevron-down"></i></span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" wire:ignore>
                    <x-dropdown-link :href="route('profile')" wire:navigate>
                        <i class="ti ti-user font-16 me-1 align-text-bottom"></i> {{ __('Profile') }}
                    </x-dropdown-link>

                    <a class="dropdown-item" href="#"><i class="ti ti-settings font-16 me-1 align-text-bottom"></i> Settings</a>
                    <div class="dropdown-divider mb-0"></div>
                    <x-dropdown-link wire:click="logout" href="javascript:void(0);">
                        <i class="ti ti-power font-16 me-1 align-text-bottom"></i> {{ __('Log Out') }}
                    </x-dropdown-link>
                </div>
            </li> --}}
            <!--end topbar-profile-->
            <li class="dropdown">



            </li>
        </ul><!--end topbar-nav-->





        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile nav-icon" id="togglemenu">
                    <i class="ti ti-menu-2"></i>
                </button>
            </li>

        </ul>
    </nav>


    <!-- end navbar-->
</div>
@script
<script>
    // Add this to your JavaScript file
    document.addEventListener('livewire:navigated', () => {
    // Make sure Bootstrap is fully loaded first
    if (typeof bootstrap !== 'undefined') {
        // Use setTimeout to ensure DOM is fully updated
        setTimeout(() => {
            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(element => {
                new bootstrap.Dropdown(element);
            });
        }, 100);
    }
});



document.addEventListener("DOMContentLoaded", function () {
    let elements = document.querySelectorAll('.dropdown-menu');
    console.log(elements);
});

</script>
@endscript
