<?php

namespace App\Livewire\Projects;

use App\Models\ProjectLog;
use App\Models\ProjectRemark;
use App\Models\ProjectTask;
use App\Models\Worksheet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ProjectDetails extends Component
{

    public $project, $allremarks, $totalRemarksCount, $projectLogs, $projectId, $remarks, $alltasks;
    public function mount($id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
            $this->projectId = $decryptedId;
            //$this->project = Worksheet::findOrFail($decryptedId);
            $this->project = Worksheet::where('id', $decryptedId)
            ->with([
                'client',
                'work',
                'projectTeamMembers',
                'projectAttachments.media',
            ])
            ->withCount([
                'projectRemarks',
                'projectTasks',
                'projectTasks as completed_tasks_count' => function ($query) {
                    $query->where('status_id', 8);
                },
            ])
            ->firstOrFail(); // throw 404 if not found

            $this->allremarks = ProjectRemark::where('project_id', $decryptedId)
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

            $this->projectLogs = ProjectLog::with(['project', 'user'])
            ->where('project_id', $decryptedId) // Filter by lead_id
            ->latest()
            ->get();

            $this->alltasks = ProjectTask::with([
                'taskTeamMembers',
                'taskAttachments.media'
            ])
            ->where('project_id', $this->projectId)
            ->withCount('taskRemarks')
            ->get();
            //   dd($this->project);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Invalid ID'
            ]));
            return redirect()->route('worksheet')->with('error', 'Invalid ID');
        }
    }


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
      //  $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Remarks Posted successfully'
        ]));
      //  redirect()->route('worksheet');
    }


    public function render()
    {
        return view('livewire.projects.project-details');
    }
}
