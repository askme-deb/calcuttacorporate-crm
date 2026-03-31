<?php

namespace App\Livewire\Projects;

use App\Models\ProjectAttachment;
use App\Models\ProjectRemark;
use App\Models\Worksheet;
use App\Models\WorkStatus;
use Carbon\Carbon;
use Livewire\Component;

class CompletedWorksheetList extends Component
{
    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadProjects'];
    public $worksheets, $worklists, $jobtypes, $leads, $deals, $price_types, $invoicetimes, $priorities, $users, $remarksModal=false;
    public $workstatus, $projectId, $status_id, $remarks, $allremarks, $totalRemarksCount;
    public $attachments = [];
    public $attachmentsModal = false, $totalAttachmentsCount, $allAttachments, $errorMessage, $imagePreview, $attachedTitle, $openStatusModal;
    public $files = [];




    public function openRemarksForm($pid){
        $this->projectId = $pid;
        $this->remarksModal = true;
        $this->allremarks = ProjectRemark::where('project_id', $this->projectId)
            ->latest()
            ->with(['commenter'])
            ->tap(function ($query) {
                $this->totalRemarksCount = $query->count();
            })
            ->get()
            ->map(function ($remark) {
                $remark->time_ago = Carbon::parse($remark->created_at)->diffForHumans();
                return $remark;
            });
    }
    public function openStatusForm($pid){
        $this->projectId = $pid;
        $this->openStatusModal = true;
        $this->workstatus = WorkStatus::pluck('name', 'id')->all();
        $project = Worksheet::findOrFail($this->projectId);
        $this->status_id = $project->status_id;
    }
    public function openAttachments($pid)
    {
        $this->projectId = $pid;
        $this->attachmentsModal = true;

        // Fetch attachments with media and attachedBy relation
        $attachments = ProjectAttachment::with('attachedBy')
            ->where('project_id', $this->projectId)
            ->latest()
            ->get();

        // Transform attachments
        $this->allAttachments = $attachments->map(function ($attachment) {
            return [
                'id' => $attachment->id,
                'title' => $attachment->title,
                'attached_by' => $attachment->attached_by,
                'attached_on' => $attachment->attached_on,
                'media' => $attachment->getMedia('project-attachment')->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                        'name' => $media->file_name,
                        'id' => $media->id,
                    ];
                })->toArray(),
            ];
        });

        // Get total count of all media across all attachments
        $this->totalAttachmentsCount = $this->allAttachments->sum(fn($attachment) => count($attachment['media']));
    }


    public function mount()
    {

        $user = auth()->user();

        // Get the status_id for "Completed" from status_master
        $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

        if ($user->can('View Projects')) {
            $this->worksheets = Worksheet::where('status_id', $completedStatusId)
            ->with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media'
            ])
            ->withCount('projectRemarks')
            ->withCount('projectTasks')
            ->withCount([
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8); // Change 3 to your actual completed status_id
                }
            ])
            ->get();
        } else {
            $this->worksheets = Worksheet::where('status_id', $completedStatusId)
            ->whereHas('projectTeamMembers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media'
            ])
            ->withCount('projectRemarks')
            ->withCount('projectTasks')
            ->withCount([
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8); // Change 3 to your actual completed status_id
                }
            ])
            ->get();
        }



        // dd(Worksheet::with('projectAttachments.media')->first());

      //  $this->workstatus = WorkStatus::pluck('name', 'id')->all();
    }

    public function render()
    {
        return view('livewire.projects.completed-worksheet-list');
    }

    public function closeModal()
    {
        $this->reset('remarksModal');
        $this->reset('attachmentsModal');
        $this->reset('openStatusModal');
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $user = auth()->user();

        if ($user->can('View Projects')) {
            $this->worksheets = Worksheet::with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media'
            ])
            ->withCount('projectRemarks')
            ->withCount('projectTasks')
            ->withCount([
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8); // Change 3 to your actual completed status_id
                }
            ])
            ->get();
        } else {
            $this->worksheets = Worksheet::whereHas('projectTeamMembers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media'
            ])
            ->withCount('projectRemarks')
            ->withCount('projectTasks')
            ->withCount([
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8); // Change 3 to your actual completed status_id
                }
            ])
            ->get();
        }




      //  $this->workstatus = WorkStatus::pluck('name', 'id')->all();

    }

    public function deleteItem($id)
    {
        $item = Worksheet::find($id);
        if ($item) {
            $item->delete();
            $this->dispatch('refreshComponent');
            $this->dispatch('swal:success', json_encode([
                'title' => 'Item Deleted',
                'text' => 'The Data has been successfully deleted.',
                'icon' => 'success',
            ]));
        }
    }


    public function download($mediaId, $fileName)
    {
        $media = Media::find($mediaId);
        if (!$media) {
            abort(404, 'Media not found');
        }
        $path = $media->getPath();
        if (!file_exists($path)) {
            abort(404, 'File not found on disk');
        }

        $this->dispatch('download-complete');
        return response()->download($path, $fileName);
    }


    public function newAttachment(){
      //  dd($this->projectId);
        $projectAttachment = ProjectAttachment::create([
            'project_id' => $this->projectId,
            'title' => $this->pull('attachedTitle'),
            'attached_by' => Auth::id(),
            'attached_on' => now(),
        ]);

        foreach ($this->files as $file) {
            $projectAttachment->addMedia($file->getPathname()) // Use getPathname() instead of getRealPath()
                ->usingFileName($file->getClientOriginalName()) // Keep original file name
                ->toMediaCollection('project-attachment'); // Store in 'uploads' collection
        }

        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Uploaded successfully'
        ]));
        $this->reset('files');

        $project = Worksheet::findOrFail($this->projectId);
        $userIds = ProjectTeamMember::where('project_id', $this->projectId)
            ->pluck('user_id')
            ->toArray(); // Fetch user IDs as an array
        // Fetch assigned users
        $assignedUsers = User::whereIn('id', $userIds)->get();
        // Send notification to each assigned user
        foreach ($assignedUsers as $user) {
            $user->notify(new MediaAttachmentNotification($project));
        }
        // Dispatch event for notification update
        $this->dispatch('notificationAdded');
        $this->openAttachments($this->projectId);
    }


    public function completedProjects()
    {

        $user = auth()->user();

        // Get the status_id for "Completed" from status_master
        $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

        if ($user->can('View Projects')) {
            $this->worksheets = Worksheet::where('status_id', $completedStatusId)
            ->with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media'
            ])
            ->withCount('projectRemarks')
            ->withCount('projectTasks')
            ->withCount([
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8); // Change 3 to your actual completed status_id
                }
            ])
            ->get();
        } else {
            $this->worksheets = Worksheet::where('status_id', $completedStatusId)
            ->whereHas('projectTeamMembers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media'
            ])
            ->withCount('projectRemarks')
            ->withCount('projectTasks')
            ->withCount([
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8); // Change 3 to your actual completed status_id
                }
            ])
            ->get();
        }



        // dd(Worksheet::with('projectAttachments.media')->first());

      //  $this->workstatus = WorkStatus::pluck('name', 'id')->all();
    }

}
