<?php

namespace App\Livewire\Hr;

use App\Models\User;
use Livewire\Component;

class DeviceInfo extends Component
{
    public $userId;
    public $model;
    public $operatingSystem;
    public $osVersion;
    public $manufacturer;
    public $uuid;
    public $timestamp;
    public $selectedUser;
    public $selectedUserId;
    public $users = [];

    protected $listeners = ['resetDeviceInfo', 'selectedUserId' => 'updatedSelectedUserId'];

    public function mount()
    {
        $this->users = User::select('id', 'name')
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Director']);
            })
            ->get()
            ->toArray();

        $this->selectedUserId = null;

        if ($this->selectedUserId) {
            $this->loadDeviceInfo($this->selectedUserId);
        }
    }



    public function updatedSelectedUserId($value)
    {
        if ($value) {
            $this->selectedUser = User::with(['roles', 'employee'])->find($value);
            $this->loadDeviceInfo($value);
        } else {
            $this->selectedUser = null;
        }
    }


    public function loadDeviceInfo($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->device_info) {
            $info = json_decode($user->device_info, true);

            $this->model           = $info['model'] ?? 'Unknown';
            $this->operatingSystem = $info['operatingSystem'] ?? 'Unknown';
            $this->osVersion       = $info['osVersion'] ?? '-';
            $this->manufacturer    = $info['manufacturer'] ?? 'Unknown';
            $this->uuid            = $info['uuid'] ?? '-';
        } else {
            $this->model = $this->operatingSystem = $this->osVersion = $this->manufacturer = $this->uuid = '-';
        }
        $this->timestamp = now()->format('d M Y, h:i A');
    }

    public function resetDeviceInfo($id)
    {
        //dd($id);
        $user = User::findOrFail($id);
        $user->device_info = null; // clear JSON
        $user->save();

        $this->loadDeviceInfo($id);

        $this->dispatch('swal:success', json_encode([
            'title' => 'Deregistered',
            'text' => 'Device has been successfully deregistered and all associated data has been reset.',
            'icon' => 'success',
        ]));
    }

    public function render()
    {
        return view('livewire.hr.device-info');
    }

    // Helper methods
    public function getThemeClass($manufacturer)
    {
        $manufacturer = strtolower($manufacturer);

        $themes = [
            'motorola' => 'theme-motorola',
            'xiaomi'   => 'theme-xiaomi',
            'samsung'  => 'theme-samsung',
            'apple'    => 'theme-apple',
            'oneplus'  => 'theme-oneplus',
            'oppo'     => 'theme-oppo',
            'realme'   => 'theme-realme',
            'vivo'     => 'theme-vivo',
        ];

        return $themes[$manufacturer] ?? '';
    }


    public function getBrandLogo($manufacturer)
    {
        $logos = [
            'Motorola' => "https://upload.wikimedia.org/wikipedia/commons/e/e5/Motorola_logo.svg",
            'Xiaomi'   => "https://upload.wikimedia.org/wikipedia/commons/2/29/Xiaomi_logo.svg",
            'Samsung'  => "https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg",
            'Apple'    => "https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg",
            'OnePlus'  => "https://upload.wikimedia.org/wikipedia/commons/c/c0/Oneplus_Logo_%282021%29.svg",
            'Oppo'     => "https://upload.wikimedia.org/wikipedia/commons/0/0a/OPPO_LOGO_2019.svg",
            'Realme'     => "https://upload.wikimedia.org/wikipedia/commons/a/a2/Realme_logo.svg",
            'Vivo'     => "https://upload.wikimedia.org/wikipedia/commons/1/13/Vivo_logo_2019.svg",
        ];
        return $logos[ucwords(strtolower($manufacturer))] ?? '';
    }

    public function getOsIcon($os)
    {
        // Normalize the OS string
        $os = ucwords(strtolower($os));

        $icons = [
            'Android' => "https://upload.wikimedia.org/wikipedia/commons/d/d7/Android_robot.svg",
            'iOS'     => "https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg",
            'Windows' => "https://upload.wikimedia.org/wikipedia/commons/5/5f/Windows_logo_-_2012.svg",
            'Linux'   => "https://upload.wikimedia.org/wikipedia/commons/3/35/Tux.svg",
        ];

        return $icons[$os] ?? '';
    }
}
