<?php

namespace App\Livewire\Emails;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmailAccount;
use Illuminate\Support\Facades\Auth;
use Webklex\IMAP\Client as ImapClient;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Exception;
use Webklex\IMAP\Facades\Client;
class EmailAccountManagement extends Component
{
     use WithPagination;

    public $emailAccountId;
    public $email_address, $name;
    public $imap_host ='imap.hostinger.com', $imap_port = 993, $imap_encryption = 'ssl';
    public $smtp_host ='smtp.hostinger.com', $smtp_port = 465, $smtp_encryption = 'ssl';
    public $smtp_username, $smtp_password;
    public $active = 1;

    public $isEditing = false;

    protected $rules = [
        'email_address' => 'required|email',
        'name' => 'nullable|string',
        'imap_host' => 'required|string',
        'imap_port' => 'required|integer',
        'imap_encryption' => 'required|string',
        'smtp_host' => 'nullable|string',
        'smtp_port' => 'required|integer',
        'smtp_encryption' => 'required|string',
        'smtp_username' => 'nullable|string',
        'smtp_password' => 'nullable|string',
        'active' => 'boolean',
    ];
    public function mount()
    {
        $this->smtp_username = Auth::check() ? Auth::user()->email : null;
    }
    public function render()
    {
        $accounts = EmailAccount::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.emails.email-account-management', compact('accounts'));
    }

    public function resetForm()
    {
        $this->reset([
            'emailAccountId', 'email_address', 'name',
            'imap_host', 'imap_port', 'imap_encryption',
            'smtp_host', 'smtp_port', 'smtp_encryption',
            'smtp_username', 'smtp_password', 'active', 'isEditing'
        ]);
        $this->imap_port = 993;
        $this->imap_encryption = 'ssl';
        $this->smtp_port = 465;
        $this->smtp_encryption = 'ssl';
        $this->active = 1;
    }

    public function save()
{
    $this->validate();

    EmailAccount::updateOrCreate(
        ['id' => $this->emailAccountId],
        [
            'user_id'         => Auth::id(),
            'email_address'   => $this->email_address,
            'name'            => $this->name,
            'imap_host'       => $this->imap_host,
            'imap_port'       => $this->imap_port,
            'imap_encryption' => $this->imap_encryption,
            'smtp_host'       => $this->smtp_host,
            'smtp_port'       => $this->smtp_port,
            'smtp_encryption' => $this->smtp_encryption,
            'smtp_username'   => $this->smtp_username,
            'smtp_password'   => $this->smtp_password,
            'active'          => $this->active,
        ]
    );

    session()->flash('success', $this->emailAccountId ? 'Account updated!' : 'Account created!');
    $this->resetForm();
}

    // public function store()
    // {
    //     $this->validate();

    //     EmailAccount::create(array_merge(
    //         $this->only([
    //             'email_address', 'name',
    //             'imap_host', 'imap_port', 'imap_encryption',
    //             'smtp_host', 'smtp_port', 'smtp_encryption',
    //             'smtp_username', 'smtp_password', 'active'
    //         ]),
    //         ['user_id' => Auth::id()]
    //     ));

    //     session()->flash('success', 'Email account added successfully.');
    //     $this->resetForm();
    // }

    public function edit($id)
    {
        $account = EmailAccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->fill($account->toArray());
        $this->emailAccountId = $account->id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $account = EmailAccount::where('id', $this->emailAccountId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $account->update($this->only([
            'email_address', 'name',
            'imap_host', 'imap_port', 'imap_encryption',
            'smtp_host', 'smtp_port', 'smtp_encryption',
            'smtp_username', 'smtp_password', 'active'
        ]));

        session()->flash('success', 'Email account updated successfully.');
        $this->resetForm();
    }

    public function delete($id)
    {
        $account = EmailAccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $account->delete();

        session()->flash('success', 'Email account deleted.');
    }



public function testConnection($accountId)
{
    $account = EmailAccount::findOrFail($accountId);

    try {
        $client = Client::make([
            'host'          => $account->imap_host,
            'port'          => $account->imap_port,
            'encryption'    => $account->imap_encryption,
            'validate_cert' => true,
            'username'      => $account->email_address,
            'password'      => $account->smtp_password, // often same as email pass/app pass
            'protocol'      => 'imap'
        ]);

        $client->connect();

        return "✅ Connection successful!";
    } catch (\Exception $e) {
        return "❌ Connection failed: " . $e->getMessage();
    }
}

}








