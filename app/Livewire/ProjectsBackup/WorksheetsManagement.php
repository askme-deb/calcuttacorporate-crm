<?php

namespace App\Livewire\Projects;

use App\Models\Deal;
use App\Models\InvoiceTime;
use App\Models\JobType;
use App\Models\Lead;
use App\Models\LeadPriority;
use App\Models\PriceType;
use App\Models\ProjectAttachment;
use App\Models\ProjectLog;
use App\Models\ProjectRemark;
use App\Models\ProjectTeamMember;
use App\Models\User;
use App\Models\WorkMaster;
use App\Models\Worksheet;
use App\Models\WorkStatus;
use App\Notifications\MediaAttachmentNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WorksheetsManagement extends Component
{
    use WithFileUploads;
    protected $listeners = ['deleteProject', 'refreshComponent' => 'loadProjects'];

    public $worksheets, $worklists, $jobtypes, $leads, $deals, $price_types, $invoicetimes, $priorities, $users, $remarksModal=false;
    public $workstatus, $projectId, $status_id, $remarks, $allremarks, $totalRemarksCount;
    public $attachments = [];
    public $attachmentsModal = false, $totalAttachmentsCount, $allAttachments, $errorMessage, $imagePreview, $attachedTitle, $openStatusModal;
    public $files = [];
    public $search = '';
    public $viewType = 'grid';
    public $projectType = '';
    public function setView($view)
    {
         $this->dispatch('closeDropdown');
        $this->viewType = $view;
    }
    public function setProjectType($projecttype)
    {
        $this->projectType = $projecttype;
        $this->dispatch('closeDropdown');
        $this->loadProjects();

    }
    public function updatedImage()
    {
        try {
            $this->validateOnly('image', [
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            // If validation passes, store the image in a temporary preview
            $this->imagePreview = $this->image->temporaryUrl();
            $this->errorMessage = null;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->imagePreview = null;
            $this->errorMessage = $e->validator->errors()->first('image');
        }
    }
    public function updatedSearch()
    {
        $this->loadProjects();
    }

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


    // public function saveStatus(){
    //     $project = Worksheet::findOrFail($this->projectId);
    //     $project->update([
    //         'status_id' => $this->pull('status_id'),
    //         'updated_at' => now(),
    //     ]);

    //     $this->dispatch('refreshComponent')->to(self::class);
    //     $this->closeModal();
    //     $this->dispatch('toastMessage', json_encode([
    //         'type'=>'success',
    //         'message' => 'Remarks Posted successfully'
    //     ]));
    //     redirect()->route('worksheet');
    // }

    public function saveStatus()
    {
        $project = Worksheet::findOrFail($this->projectId);
        // Fetch the status from status_master
        $status = WorkStatus::where('id', $this->status_id)->value('name');
        //dd($status);
        // Prepare update data
        $updateData = [
            'status_id' => $this->pull('status_id'),
            'updated_at' => now(),
        ];
        // If status is "Completed", update additional fields
        if (strtolower($status) === 'completed') {

            $updateData['completed_on'] = now();
            $updateData['completed_by'] = auth()->id(); // Assuming user authentication is enabled
        }
        // Update the project record
        $project->update($updateData);
        // Refresh the component and close the modal

        // Log the upload action
        ProjectLog::create([
            'project_id' => $this->projectId,
            'user_id' => auth()->id(),
            'action' => 'Status Updated',
            'notes' => 'Updated Project Status by ' . auth()->user()->name,
        ]);

        $this->dispatch('refreshComponent')->to(self::class);
        $this->closeModal();
        // Show success message
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Remarks Posted successfully'
        ]));
        // Redirect back to the worksheet route
        return redirect()->route('worksheet');
    }



    public function mount()
    {
        $this->jobtypes = JobType::pluck('name', 'id')->all();
        $this->loadProjects();
        // $user = auth()->user();
        // $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

        // // Build the base query
        // $query = Worksheet::where('status_id', '!=', $completedStatusId)
        //     ->with([
        //         'client',
        //         'work',
        //         'projectTeamMembers',
        //         'projectAttachments.media'
        //     ])
        //     ->withCount('projectRemarks')
        //     ->withCount('projectTasks')
        //     ->withCount([
        //         'projectTasks as completed_tasks_count' => function ($query) {
        //             $query->where('status_id', 8); // Change 8 to your actual completed status_id if needed
        //         }
        //     ])
        //     ->orderBy('id', 'desc');

        // // If user cannot view all projects, restrict to their projects
        // if (!$user->can('View Projects')) {
        //     $query->whereHas('projectTeamMembers', function ($q) use ($user) {
        //         $q->where('user_id', $user->id);
        //     });
        // }

        // // Apply search filters if search is not empty
        // if (!empty($this->search)) {
        //     $query->where(function ($q) {
        //         $q->where('title', 'like', '%' . $this->search . '%')
        //             ->orWhereHas('client', function ($q) {
        //                 $q->where('name', 'like', '%' . $this->search . '%');
        //             })
        //             ->orWhereHas('work', function ($q) {
        //                 $q->where('name', 'like', '%' . $this->search . '%');
        //             });
        //     });
        // }

        // // Finally get the result
        // $this->worksheets = $query->get();
    }



    // public function mount()
    // {
    //     $user = auth()->user();
    //     // Get the status_id for "Completed" from status_master
    //     $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

    //     if ($user->can('View Projects')) {
    //         $this->worksheets = Worksheet::where('status_id', '!=', $completedStatusId)
    //             ->with([
    //                 'client',
    //                 'work',
    //                 'projectTeamMembers',
    //                 'projectAttachments.media'
    //             ])
    //             ->withCount('projectRemarks')
    //             ->withCount('projectTasks')
    //             ->withCount([
    //                 'projectTasks as completed_tasks_count' => function ($query) {
    //                     $query->where('status_id', 8); // Change 3 to your actual completed status_id
    //                 }
    //             ])
    //             ->orderBy('id', 'desc')
    //             ->get();

    //         // Apply search filters
    //         if (!empty($this->search)) {
    //             $query->where(function ($q) {
    //                 $q->where('title', 'like', '%' . $this->search . '%')
    //                     ->orWhereHas('client', function ($query) {
    //                         $query->where('name', 'like', '%' . $this->search . '%');
    //                     })
    //                     ->orWhereHas('work', function ($query) {
    //                         $query->where('name', 'like', '%' . $this->search . '%');
    //                     });
    //             });
    //         }
    //     } else {
    //         $this->worksheets = Worksheet::where('status_id', '!=', $completedStatusId)
    //             ->whereHas('projectTeamMembers', function ($query) use ($user) {
    //                 $query->where('user_id', $user->id);
    //             })
    //             ->with([
    //                 'client',
    //                 'work',
    //                 'projectTeamMembers',
    //                 'projectAttachments.media'
    //             ])
    //             ->withCount('projectRemarks')
    //             ->withCount('projectTasks')
    //             ->withCount([
    //                 'projectTasks as completed_tasks_count' => function ($query) {
    //                     $query->where('status_id', 8); // Change 3 to your actual completed status_id
    //                 }
    //             ])
    //             ->orderBy('id', 'desc')
    //             ->get();
    //                   // Apply search filters
    //         if (!empty($this->search)) {
    //             $query->where(function ($q) {
    //                 $q->where('title', 'like', '%' . $this->search . '%')
    //                     ->orWhereHas('client', function ($query) {
    //                         $query->where('name', 'like', '%' . $this->search . '%');
    //                     })
    //                     ->orWhereHas('work', function ($query) {
    //                         $query->where('name', 'like', '%' . $this->search . '%');
    //                     });
    //             });
    //         }
    //     }



    //     // dd(Worksheet::with('projectAttachments.media')->first());

    //     //  $this->workstatus = WorkStatus::pluck('name', 'id')->all();
    // }



    public function saveRemarks(){
        ProjectRemark::create(
            [
                'user_id' => Auth::user()->id,
               // 'status_id' => $this->status_id,
                'project_id' => $this->projectId,
                'remarks' => $this->pull('remarks'),
                'is_visible' => 1,
            ]
        );
        $project = Worksheet::findOrFail($this->projectId);
        $project->update([
          //  'status_id' => $this->pull('status_id'),
            'updated_at' => now(),
        ]);

        // Log the upload action
        ProjectLog::create([
            'project_id' => $this->projectId,
            'user_id' => auth()->id(),
            'action' => 'New Comment',
            'notes' => 'Commented by ' . auth()->user()->name,
        ]);

        $this->dispatch('refreshComponent')->to(self::class);
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Remarks Posted successfully'
        ]));
        redirect()->route('worksheet');
    }


    public function render()
    {
        if($this->viewType == 'grid'){
            $template = 'worksheets-management';
        }else{
            $template = 'worksheets-management-list';
        }
        return view('livewire.projects.'.$template);
    }

    public function closeModal()
    {
        $this->reset('remarksModal');
        $this->reset('attachmentsModal');
        $this->reset('openStatusModal');
        $this->loadProjects();
    }

    // public function loadProjects()
    // {
    //     $user = auth()->user();
    //     $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

    //     $query = Worksheet::query()
    //         ->select('worksheets.*')
    //         ->selectSub(function ($q) {
    //             $q->from('project_remarks')
    //               ->selectRaw('COUNT(*)')
    //               ->whereColumn('worksheets.id', 'project_remarks.project_id');
    //         }, 'project_remarks_count')
    //         ->selectSub(function ($q) {
    //             $q->from('project_tasks')
    //               ->selectRaw('COUNT(*)')
    //               ->whereColumn('worksheets.id', 'project_tasks.project_id');
    //         }, 'project_tasks_count')
    //         ->selectSub(function ($q) {
    //             $q->from('project_tasks')
    //               ->selectRaw('COUNT(*)')
    //               ->whereColumn('worksheets.id', 'project_tasks.project_id')
    //               ->where('status_id', 8);
    //         }, 'completed_tasks_count')
    //         ->where('status_id', '!=', $completedStatusId)
    //         ->where('created_by', $user->id)
    //         ->orderBy('id', 'desc');

    //     if ($this->projectType !== null && $this->projectType !== '' && $this->projectType !== '0') {
    //         $query->where('jobtype_id', $this->projectType);
    //     }

    //     if (!empty($this->search)) {
    //         $query->where(function ($q) {
    //             $q->where('title', 'like', '%' . $this->search . '%')
    //               ->orWhereHas('client', function ($q) {
    //                   $q->where('name', 'like', '%' . $this->search . '%');
    //               })
    //               ->orWhereHas('work', function ($q) {
    //                   $q->where('name', 'like', '%' . $this->search . '%');
    //               });
    //         });
    //     }
    //   //  dd($query->toSql(), $query->getBindings());
    //     $this->worksheets = $query->get();
    // }

    public function loadProjects()
    {
        $user = auth()->user();
        $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

        $query = Worksheet::where('status_id', '!=', $completedStatusId)
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
                    $query->where('status_id', 8);
                }
            ])
            ->orderBy('id', 'desc');

        if (!$user->can('View Projects')) {
            $query->whereHas('projectTeamMembers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $query->orwhere('created_by', $user->id);
        }


        if ($this->projectType !== null && $this->projectType !== '' && $this->projectType !== '0') {

            $query->where('jobtype_id', $this->projectType);
        }

        if (!empty($this->search)) { // make sure $this->search is set before calling
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('work', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }
       // dd($query->toSql(), $query->getBindings());

        $this->worksheets = $query->get();
    }


    // public function loadProjects()
    // {
    //     $user = auth()->user();
    //     $completedStatusId = WorkStatus::where('name', 'Completed')->value('id');

    //     // Build the base query
    //     $query = Worksheet::where('status_id', '!=', $completedStatusId)
    //         ->with([
    //             'client',
    //             'work',
    //             'projectTeamMembers',
    //             'projectAttachments.media'
    //         ])
    //         ->withCount('projectRemarks')
    //         ->withCount('projectTasks')
    //         ->withCount([
    //             'projectTasks as completed_tasks_count' => function ($query) {
    //                 $query->where('status_id', 8); // Change 8 to your actual completed status_id if needed
    //             }
    //         ])
    //         ->orderBy('id', 'desc');

    //     // If user cannot view all projects, restrict to their projects
    //     if (!$user->can('View Projects')) {
    //         $query->whereHas('projectTeamMembers', function ($q) use ($user) {
    //             $q->where('user_id', $user->id);
    //         });
    //     }

    //     // Apply search filters if search is not empty
    //     if (!empty($this->search)) {
    //         $query->where(function ($q) {
    //             $q->where('title', 'like', '%' . $this->search . '%')
    //                 ->orWhereHas('client', function ($q) {
    //                     $q->where('name', 'like', '%' . $this->search . '%');
    //                 })
    //                 ->orWhereHas('work', function ($q) {
    //                     $q->where('name', 'like', '%' . $this->search . '%');
    //                 });
    //         });
    //     }

    //     // Finally get the result
    //     $this->worksheets = $query->get();

        // $user = auth()->user();

        // if ($user->can('View Projects')) {
        //     $this->worksheets = Worksheet::with([
        //         'client',
        //         'work',
        //         'projectTeamMembers',
        //         'projectAttachments.media'
        //     ])
        //     ->withCount('projectRemarks')
        //     ->withCount('projectTasks')
        //     ->withCount([
        //         'projectTasks as completed_tasks_count' => function ($query) {
        //             $query->where('status_id', 8); // Change 3 to your actual completed status_id
        //         }
        //     ])
        //     ->orderBy('id', 'desc')
        //     ->get();
        // } else {
        //     $this->worksheets = Worksheet::whereHas('projectTeamMembers', function ($query) use ($user) {
        //         $query->where('user_id', $user->id);
        //     })
        //     ->with([
        //         'client',
        //         'work',
        //         'projectTeamMembers',
        //         'projectAttachments.media'
        //     ])
        //     ->withCount('projectRemarks')
        //     ->withCount('projectTasks')
        //     ->withCount([
        //         'projectTasks as completed_tasks_count' => function ($query) {
        //             $query->where('status_id', 8); // Change 3 to your actual completed status_id
        //         }
        //     ])
        //     ->orderBy('id', 'desc')
        //     ->get();
        // }




      //  $this->workstatus = WorkStatus::pluck('name', 'id')->all();

   // }

    // public function deleteItem($id)
    // {
    //     $item = Worksheet::find($id);
    //     if ($item) {
    //         $item->delete();
    //         $this->dispatch('refreshComponent');
    //         $this->dispatch('swal:success', json_encode([
    //             'title' => 'Item Deleted',
    //             'text' => 'The Data has been successfully deleted.',
    //             'icon' => 'success',
    //         ]));
    //     }
    // }


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

        // Log the upload action
        ProjectLog::create([
            'project_id' => $this->projectId,
            'user_id' => auth()->id(),
            'action' => 'New File Uploaded',
            'notes' => 'Uploaded new files by ' . auth()->user()->name,
        ]);

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



    public function deleteProject($id)
    {
        $user = auth()->user();

        $worksheet = Worksheet::with([
            'projectTeamMembers',
            'projectAttachments.media',
            'projectRemarks',
            'projectTasks'
        ])->findOrFail($id);

        if (! $user->can('Delete Project')) {
            // Else, allow delete only if user is a team member
            $isTeamMember = $worksheet->projectTeamMembers->contains('user_id', $user->id);

            if (! $isTeamMember) {
                abort(403, 'You are not authorized to delete this project.');
            }
        }

        $worksheet->projectTeamMembers()->delete();

        foreach ($worksheet->projectAttachments as $attachment) {
            if ($attachment->hasMedia()) {
                $attachment->clearMediaCollection(); // deletes files from storage
            }
            $attachment->delete();
        }

        $worksheet->projectRemarks()->delete();
        $worksheet->projectTasks()->delete();
        $worksheet->delete();
        $this->dispatch('refreshComponent');
        $this->dispatch('swal:success', json_encode([
            'title' => 'Item Deleted',
            'text' => 'Project and related data deleted successfully.',
            'icon' => 'success',
        ]));
    }



}
