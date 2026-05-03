<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class LeadAttachmentManager extends Component
{
    use WithFileUploads;

    public $leadId;

    public $attachments = [];

    public $files = [];

    public $statusMessage = '';

    public $statusType = 'success';

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->refreshAttachments();
    }

    public function saveFiles()
    {
        Log::info('LeadAttachmentManager::saveFiles() called', [
            'leadId' => $this->leadId,
            'filesCount' => count($this->files),
        ]);

        $this->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png,webp,txt',
        ]);

        try {
            $lead = Lead::findOrFail($this->leadId);

            foreach ($this->files as $file) {
                $lead->addMedia($file)
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('lead-attachments');
            }

            $uploadedCount = count($this->files);

            $this->logLeadActivity('attachment', "{$uploadedCount} file(s) attached.");

            $this->reset('files');
            $this->refreshAttachments();

            $this->statusType = 'success';
            $this->statusMessage = "{$uploadedCount} file(s) uploaded successfully.";

            $this->dispatch('refreshActivityTimeline');
            $this->dispatch('toastMessage', json_encode([
                'type' => 'success',
                'message' => $this->statusMessage,
            ]));

        } catch (Throwable $e) {
            report($e);

            $this->statusType = 'danger';
            $this->statusMessage = $this->formatErrorMessage('Upload failed.', $e);

            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => $this->statusMessage,
            ]));
        }
    }

    public function deleteAttachment($mediaId)
    {
        try {
            $lead = Lead::findOrFail($this->leadId);
            $mediaItem = $lead->media()->where('id', $mediaId)->first();

            if ($mediaItem) {
                $fileName = $mediaItem->file_name;
                $mediaItem->delete();
                $this->logLeadActivity('attachment', "Attachment deleted: {$fileName}");
                $this->statusType = 'success';
                $this->statusMessage = 'Attachment deleted successfully.';
            } else {
                $this->statusType = 'danger';
                $this->statusMessage = 'Attachment not found.';
            }

            $this->mount($this->leadId);
            $this->dispatch('refreshActivityTimeline');
            $this->dispatch('toastMessage', json_encode([
                'type' => $this->statusType === 'success' ? 'success' : 'error',
                'message' => $this->statusMessage,
            ]));
        } catch (Throwable $e) {
            report($e);
            $this->statusType = 'danger';
            $this->statusMessage = $this->formatErrorMessage('Delete failed. Please try again.', $e);
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => $this->statusMessage,
            ]));
        }
    }

    private function logLeadActivity(string $type, string $description): void
    {
        LeadActivity::create([
            'lead_id'     => $this->leadId,
            'type'        => $type,
            'description' => $description,
            'activity_at' => now(),
            'user_id'     => auth()->id(),
        ]);
    }

    private function formatErrorMessage(string $defaultMessage, Throwable $e): string
    {
        if (app()->environment('local')) {
            return $defaultMessage.' ('.$e->getMessage().')';
        }

        return $defaultMessage;
    }

    public function render()
    {
        return view('livewire.leads.lead-attachment-manager');
    }

    public function refreshAttachments()
    {
        $this->attachments = Lead::findOrFail($this->leadId)
            ->getMedia('lead-attachments')
            ->sortByDesc('created_at')
            ->values();
    }
}
