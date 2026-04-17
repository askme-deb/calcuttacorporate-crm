<?php

namespace App\Livewire\Accounts\Banks;

use App\Models\BankAccount;
use Livewire\Component;
use Livewire\WithFileUploads;

class BankAccountManagement extends Component
{
    use WithFileUploads;

    public $account_holder_name, $bank_name, $branch_name, $account_no, $ifsc_code;
    public $account_type, $upi_id, $opening_status, $opening_balance, $opening_date, $bank_id,$qr;
    public $accountFormModal = false, $modalMode = null;
    public $bankAccounts = [];
    public $selectedAccount = null;
    public $isEditing = false;
    public $file = null;

    protected $rules = [
        'account_holder_name' => 'required|string|max:255',
        'bank_name' => 'required|string|max:255',
        'branch_name' => 'required|string|max:255',
        'account_no' => 'required|numeric|digits_between:8,20',
        'ifsc_code' => 'required|string|max:11',
        'account_type' => 'required|in:savings,current',
        'opening_status' => 'required|in:1,0',
        'opening_balance' => 'required|numeric|min:0',
        'opening_date' => 'required|date',
        'upi_id' => 'nullable|string|max:255',
        'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    protected $listeners = ['deleteItem'];

    public function updatedFile()
    {
        $this->validateOnly('file');
    }

    public function mount()
    {
        $this->bankAccounts = BankAccount::orderBy('is_default', 'desc')->get();
        if ($this->bankAccounts->isNotEmpty()) {
            $this->selectedAccount = $this->bankAccounts->first();
        }
    }

    public function selectAccount($accountId)
    {
        $this->selectedAccount = BankAccount::find($accountId);
    }

    public function newAccountPopup()
    {
        $this->modalMode = 'Create';
        $this->accountFormModal = true;
        $this->resetValidation();
    }

    public function editAccount($id)
    {
        $this->accountFormModal = true;
        $bankDetails = BankAccount::findOrFail($id);

        $this->account_holder_name = $bankDetails->account_holder_name;
        $this->bank_name = $bankDetails->bank_name;
        $this->branch_name = $bankDetails->branch_name;
        $this->account_no = $bankDetails->account_no;
        $this->ifsc_code = $bankDetails->ifsc_code;
        $this->account_type = $bankDetails->account_type;
        $this->opening_status = $bankDetails->opening_status;
        $this->opening_balance = $bankDetails->opening_balance;
        $this->opening_date = $bankDetails->opening_date;
        $this->upi_id = $bankDetails->upi_id;
        $this->bank_id = $bankDetails->id;
        $this->qr = $bankDetails->getFirstMediaUrl('bank-qr');

        $this->isEditing = true;
        $this->resetValidation();
    }

    public function saveAccount()
    {
        $validatedData = $this->validate();

        $newAccount = BankAccount::create($validatedData);

        if ($this->file) {
            $newAccount->addMedia($this->file->getRealPath())
                ->usingFileName($this->file->getClientOriginalName())
                ->toMediaCollection('bank-qr');
        }

        $this->bankAccounts = BankAccount::orderBy('created_at', 'desc')->get();
        $this->selectedAccount = $newAccount;

        $this->closeModal();

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Bank Account added successfully!'
        ]));
    }

    public function update()
    {
        $validatedData = $this->validate();
        $bank = BankAccount::findOrFail($this->bank_id);

        $bank->update($validatedData);

        if ($this->file) {
            $bank->clearMediaCollection('bank-qr');

            $bank->addMedia($this->file->getRealPath())
                ->usingFileName($this->file->getClientOriginalName())
                ->toMediaCollection('bank-qr');
        }

        $this->bankAccounts = BankAccount::orderBy('created_at', 'desc')->get();
        $this->selectedAccount = $bank;

        $this->closeModal();

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Bank Account updated successfully!'
        ]));
    }

    public function setAsDefaultAccount($id)
    {
        $account = BankAccount::find($id);

        if (!$account) {
            $this->dispatch('swal:error', json_encode([
                'title' => 'Error',
                'text' => 'Bank account not found.',
                'icon' => 'error',
            ]));
            return;
        }

        BankAccount::where('is_default', 1)->update(['is_default' => 0]);
        $account->update(['is_default' => 1]);

        $this->bankAccounts = BankAccount::orderBy('created_at', 'desc')->get();

        if ($this->selectedAccount && $this->selectedAccount->id == $id) {
            $this->selectedAccount = $account;
        }

        $this->dispatch('swal:success', json_encode([
            'title' => 'Default Set',
            'text' => 'Bank Account set as default successfully!',
            'icon' => 'success',
        ]));
    }

    public function removeQr()
    {
        if ($this->selectedAccount) {
            $this->selectedAccount->clearMediaCollection('bank-qr');
            $this->selectedAccount->refresh();
        }
    }

    public function deleteItem($id)
    {
        $account = BankAccount::find($id);

        if ($account) {
            $account->delete();
        }

        $this->bankAccounts = BankAccount::orderBy('created_at', 'desc')->get();

        if ($this->selectedAccount && $this->selectedAccount->id == $id) {
            $this->selectedAccount = null;
        }

        $this->dispatch('swal:success', json_encode([
            'title' => 'Item Deleted',
            'text' => 'Bank Account deleted successfully!',
            'icon' => 'success',
        ]));
    }

    public function closeModal()
    {
        $this->reset(
            'account_holder_name',
            'bank_name',
            'branch_name',
            'account_no',
            'ifsc_code',
            'account_type',
            'upi_id',
            'opening_status',
            'opening_balance',
            'opening_date',
            'file',
            'bank_id',
            'accountFormModal',
            'isEditing',
            'qr',
            'file'
        );

        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.accounts.banks.bank-account-management');
    }
}
