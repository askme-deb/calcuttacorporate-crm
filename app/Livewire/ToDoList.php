<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ToDoList extends Component
{
    public $tasks;
    public $taskName;

    public function mount()
    {
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks = Todo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function addTask()
    {
        $this->validate([
            'taskName' => 'required|string|max:255'
        ]);

        Todo::create([
            'user_id' => Auth::user()->id,
            'name' => $this->taskName,
            'completed' => false,
        ]);

        $this->taskName = '';
        $this->loadTasks();
    }

    public function toggleTask($taskId)
    {
        $task = Todo::find($taskId);
        if ($task) {
            $task->update(['completed' => !$task->completed]);
            $this->loadTasks();
        }
    }

    public function deleteTask($taskId)
    {
        Todo::find($taskId)?->delete();
        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.to-do-list');
    }
}
