<?php


namespace App\Livewire\Emails;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\EmailService;
use App\Models\Email;

class EmailReader extends Component
{
    use WithPagination;

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

    public function boot(EmailService $emailService)
    {
        $this->emailService = $emailService;
        $this->updateNewEmailCounts();
    }

    public function mount()
    {
        $this->loadEmails();
    }

    public function loadFolder($folder)
    {
        $this->folder = $folder;
        $this->resetPage();
        $this->selectedEmail = null;
        $this->loadEmails();
    }

    /**
     * Load emails from DB only
     * This will include new emails synced by cron
     */
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

    public function openEmail($uid)
    {
        $email = Email::where('folder', $this->folder)
                     ->where('uid', $uid)
                     ->firstOrFail();

        $data = $this->emailService->getEmailWithAttachmentsFromDB($email->id);

        // Mark as seen in DB and IMAP
        if (!$email->seen) {
            $email->update(['seen' => true]);
            $this->emailService->markAsRead($uid, $this->folder);
            $this->newEmailUids = array_diff($this->newEmailUids, [$uid]);
        }

        $this->selectedEmail = [
            'email' => $email,
            'attachments' => $data['attachments'],
        ];
    }

    /**
     * Only check DB for new email counts
     */
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

// namespace App\Livewire\Emails;

// use Livewire\Component;
// use Livewire\WithPagination;
// use App\Services\EmailService;
// use App\Models\Email;

// class EmailReader extends Component
// {
//     use WithPagination;

//     public $folder = 'INBOX';
//     public $selectedEmail = null;
//     public $perPage = 15;
//     public $emails = [];
//     public $total = 0;
//     public $totalPages = 1;
//     public $newEmailCounts = [];
//     public $newEmailUids = [];

//     protected $paginationTheme = 'bootstrap';
//     protected $emailService;

//     public function boot(EmailService $emailService)
//     {
//         $this->emailService = $emailService;
//         $this->updateNewEmailCounts();
//     }

//     public function mount()
//     {
//         $this->loadEmails();
//     }

//     public function loadFolder($folder)
//     {
//         $this->folder = $folder;
//         $this->resetPage();
//         $this->selectedEmail = null;
//         $this->loadEmails();
//     }

//     public function loadEmails()
//     {
//         $data = $this->emailService->fetchEmailsFromDB($this->folder, $this->getPage(), $this->perPage);

//         $this->emails = $data['emails'];
//         $this->total = $data['pagination']['total'];
//         $this->totalPages = $data['pagination']['total_pages'];
//         $this->newEmailUids = $this->emails->where('seen', false)->pluck('uid')->toArray();
//     }

//     public function openEmail($uid)
//     {
//         // Find the email by UID and folder
//         $email = Email::where('folder', $this->folder)
//                      ->where('uid', $uid)
//                      ->firstOrFail();

//         $data = $this->emailService->getEmailWithAttachmentsFromDB($email->id);

//         // Mark as seen in DB and IMAP
//         if (!$email->seen) {
//             $email->update(['seen' => true]);
//             $this->emailService->markAsRead($uid, $this->folder);
//             $this->newEmailUids = array_diff($this->newEmailUids, [$uid]);
//         }

//         $this->selectedEmail = [
//             'email' => $email,
//             'attachments' => $data['attachments'],
//         ];
//     }

//     public function pollNewEmails()
//     {
//         $this->emailService->syncEmails($this->folder, 50);
//         $this->updateNewEmailCounts();
//         $this->loadEmails();
//     }

//     public function updateNewEmailCounts()
//     {
//         $folders = ['INBOX', '[Gmail]/Sent Mail', '[Gmail]/Drafts', '[Gmail]/Trash'];
//         foreach ($folders as $folder) {
//             $this->newEmailCounts[$folder] = $this->emailService->getNewEmailCount($folder);
//         }
//     }

//     public function previousPage()
//     {
//         $this->setPage(max(1, $this->getPage() - 1));
//         $this->loadEmails();
//     }

//     public function nextPage()
//     {
//         $this->setPage(min($this->totalPages, $this->getPage() + 1));
//         $this->loadEmails();
//     }

//     public function gotoPage($page)
//     {
//         $this->setPage($page);
//         $this->loadEmails();
//     }

//     public function updatingPage()
//     {
//         $this->selectedEmail = null;
//     }

//     public function moveToTrash($uid)
//     {
//         if ($this->emailService->moveEmail($uid, $this->folder, '[Gmail]/Trash')) {
//             $this->loadEmails(); // Refresh the list
//             $this->selectedEmail = null;
//             session()->flash('message', 'Email moved to trash successfully.');
//         } else {
//             session()->flash('error', 'Failed to move email to trash.');
//         }
//     }

//     public function render()
//     {
//         return view('livewire.emails.email-reader', [
//             'emails' => $this->emails,
//             'selectedEmail' => $this->selectedEmail,
//             'newEmailUids' => $this->newEmailUids,
//             'totalPages' => $this->totalPages,
//             'currentPage' => $this->getPage(),
//             'newEmailCounts' => $this->newEmailCounts,
//         ]);
//     }
// }
