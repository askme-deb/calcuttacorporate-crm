<?php

namespace App\Livewire\Emails;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Services\EmailService;
use App\Models\Email;

class EmailReader extends Component
{
    use WithPagination, WithFileUploads;

    public $conversation = [];
    public $replyBody;
    public $attachments = [];
    public $folder = 'INBOX';
    public $selectedEmail = null;
    public $perPage = 15;
    public $emails = [];
    public $total = 0;
    public $totalPages = 1;
    public $newEmailCounts = [];
    public $newEmailUids = [];

    protected $paginationTheme = 'bootstrap';
    protected $emailService;

    protected $rules = [
        'replyBody' => 'required|string',
        'attachments.*' => 'file|max:10240', // max 10MB per file
    ];

    public function boot(EmailService $emailService)
    {
        $this->emailService = $emailService;
        $this->updateNewEmailCounts();
    }

    public function mount()
    {
        $this->loadEmails();
    }

    // public function loadConversation($emailId)
    // {
    //     $email = Email::findOrFail($emailId);
    //     $this->conversation = $this->emailService->getConversation($email);
    // }
public function loadConversation($emailId)
{
    $originalEmail = Email::find($emailId);

    if (!$originalEmail) {
        session()->flash('error', 'Email not found.');
        return;
    }

    // Get threaded conversation
    $this->conversation = $this->emailService->getThreadedConversation($originalEmail);
}
public function loadConversationThread(Email $email)
{
    // Eager load replies recursively
    $email->load(['replies' => function ($query) {
        $query->orderBy('date', 'asc');
    }]);

    $this->conversation = $email;
}
public function sendReply(bool $replyAll = false)
{
    $this->validate([
        'replyBody' => 'required|string',
        'attachments.*' => 'file|max:10240', // max 10MB per file
    ]);

    if (!$this->selectedEmail || !isset($this->selectedEmail['email'])) {
        $this->dispatch('toastMessage', json_encode([
            'type' => 'error',
            'message' => 'No email selected.'
        ]));
        return;
    }

    $originalEmail = $this->selectedEmail['email'];
    //dd($originalEmail->email_account_id);
    // Ensure the Email model has an account loaded
    if (!$originalEmail instanceof \App\Models\Email || !$originalEmail->email_account_id) {
        $this->dispatch('toastMessage', json_encode([
            'type' => 'error',
            'message' => 'Original email does not have an associated email account.'
        ]));
        return;
    }

    // Eager load account if not loaded
    if (!$originalEmail->relationLoaded('account')) {
        $originalEmail->load('account');
    }

    if (!$originalEmail->account) {
        $this->dispatch('toastMessage', json_encode([
            'type' => 'error',
            'message' => 'Email account not found. Please check the email settings.'
        ]));
        return;
    }

    $bodyHtml = nl2br(e($this->replyBody));
    $bodyPlain = $this->replyBody;

    $emailService = app(\App\Services\EmailService::class);

    // Send email via PHPMailer and get Message-ID
    $messageId = $emailService->replyToEmail(
        $originalEmail,
        $bodyHtml,
        $bodyPlain,
        $this->attachments,
        $replyAll
    );

    if (!$messageId) {
        $this->dispatch('toastMessage', json_encode([
            'type' => 'error',
            'message' => 'Failed to send reply. Check SMTP settings or server logs.'
        ]));
        return;
    }

    // Save reply in the database (existing logic)
    $replyEmail = Email::create([
        'subject'          => 'Re: ' . $originalEmail->subject,
        'from'             => $originalEmail->account->email_address,
        'to'               => $replyAll ? $originalEmail->to : $originalEmail->from,
        'cc'               => $replyAll ? $originalEmail->cc : null,
        'bcc'              => $replyAll ? $originalEmail->bcc : null,
        'folder'           => 'Sent',
        'date'             => now(),
        'seen'             => true,
        'reply_to_id'      => $originalEmail->id,
        'message_id'       => $messageId,
        'in_reply_to'      => $originalEmail->message_id,
        'body'             => $bodyHtml,
        'body_plain'       => $bodyPlain,
        'user_id'          => $originalEmail->user_id,
        'email_account_id' => $originalEmail->email_account_id,
    ]);

    // Save attachments
    if (!empty($this->attachments)) {
        foreach ($this->attachments as $file) {
            $replyEmail->addMedia($file->getRealPath())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('attachments');
        }
    }

    // Reset form fields
    $this->reset(['replyBody', 'attachments']);

    // Refresh selected email
    $this->selectedEmail = [
        'email' => $originalEmail->fresh(),
        'attachments' => $this->selectedEmail['attachments'] ?? []
    ];

    $this->dispatch('toastMessage', json_encode([
        'type' => 'success',
        'message' => $replyAll ? 'Reply All sent successfully.' : 'Reply sent successfully.'
    ]));
}


    public function loadFolder($folder)
    {
        $this->folder = $folder;
        $this->resetPage();
        $this->selectedEmail = null;
        $this->loadEmails();
    }

    public function loadEmails()
    {
        $data = $this->emailService->fetchEmailsFromDB(
            $this->folder,
            $this->getPage(),
            $this->perPage
        );

        $this->emails = $data['emails'];
        $this->total = $data['pagination']['total'];
        $this->totalPages = $data['pagination']['total_pages'];
        $this->newEmailUids = $this->emails->where('seen', false)->pluck('uid')->toArray();
        $this->updateNewEmailCounts();
    }

    // public function openEmail($uid)
    // {
    //     $email = Email::where('folder', $this->folder)
    //                  ->where('uid', $uid)
    //                  ->firstOrFail();

    //     $data = $this->emailService->getEmailWithAttachmentsFromDB($email->id);

    //     if (!$email->seen) {
    //         $email->update(['seen' => true]);
    //         $this->emailService->markAsRead($uid, $this->folder);
    //         $this->newEmailUids = array_diff($this->newEmailUids, [$uid]);
    //     }

    //     $this->selectedEmail = [
    //         'email' => $email,
    //         'attachments' => $data['attachments'],
    //     ];

    //     $this->loadConversation($email->id);
    // }
// public function openEmail($uid)
// {
//     $email = Email::where('uid', $uid)->first();
//     if (!$email) {
//         session()->flash('error', 'Email not found.');
//         return;
//     }

//     // Load email attachments
//     $attachments = $email->getMedia('attachments')->map(function($file){
//         return [
//             'url' => $file->getUrl(),
//             'name' => $file->file_name,
//         ];
//     })->toArray();

//     // Load conversation thread recursively
//     $this->conversation = $email;

//     $this->selectedEmail = [
//         'email' => $email,
//         'attachments' => $attachments,
//     ];
// }
public function openEmail($uid)
{
    $email = Email::where('uid', $uid)->first();
    if (!$email) {
        session()->flash('error', 'Email not found.');
        return;
    }

            if (!$email->seen) {
            $email->update(['seen' => true]);
             $this->emailService->markAsRead($uid, $this->folder);
             $this->newEmailUids = array_diff($this->newEmailUids, [$uid]);
        }
    // Load attachments
    $attachments = $email->getMedia('attachments')->map(function($file){
        return [
            'url' => $file->getUrl(),
            'name' => $file->file_name,
        ];
    })->toArray();

    $this->selectedEmail = [
        'email' => $email,
        'attachments' => $attachments,
    ];

    $this->loadConversationThread($email);
}

    public function updateNewEmailCounts()
    {
        $folders = ['INBOX', '[Gmail]/Sent Mail', '[Gmail]/Drafts', '[Gmail]/Trash'];
        foreach ($folders as $folder) {
            $this->newEmailCounts[$folder] = $this->emailService->getNewEmailCount($folder);
        }
    }

    public function previousPage()
    {
        $this->setPage(max(1, $this->getPage() - 1));
        $this->loadEmails();
    }

    public function nextPage()
    {
        $this->setPage(min($this->totalPages, $this->getPage() + 1));
        $this->loadEmails();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->loadEmails();
    }

    public function updatingPage()
    {
        $this->selectedEmail = null;
    }

    public function moveToTrash($uid)
    {
        if ($this->emailService->moveEmail($uid, $this->folder, '[Gmail]/Trash')) {
            $this->loadEmails();
            $this->selectedEmail = null;
            session()->flash('message', 'Email moved to trash successfully.');
        } else {
            session()->flash('error', 'Failed to move email to trash.');
        }
    }

    public function render()
    {
        return view('livewire.emails.email-reader', [
            'emails' => $this->emails,
            'selectedEmail' => $this->selectedEmail,
            'newEmailUids' => $this->newEmailUids,
            'totalPages' => $this->totalPages,
            'currentPage' => $this->getPage(),
            'newEmailCounts' => $this->newEmailCounts,
        ]);
    }
}
