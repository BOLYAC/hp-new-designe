<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class Tasks extends Component
{
    public $tasks, $title, $date, $mode, $client, $updateMode, $taskId;

    protected $rules = [
        'title' => 'required|string|min:6',
        'date' => 'required|date',
    ];

    public function mount($client)
    {
        $this->mode = 'show';
        $this->tasks = Task::all();
        $this->client = $client->id;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function render()
    {
        $client = $this->client;
        $this->tasks = Task::whereHas('client', function ($query) use ($client) {
            $query->where('client_id', $client);
        })->get()->sortByDesc('created_at');
        return view('livewire.tasks');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    private function resetInputFields()
    {
        $this->title = '';
        $this->date = '';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function createTask()
    {
        $this->validate();

        $task = [
            'title' => $this->title,
            'date' => $this->date,
            'user_id' => Auth::id(),
            'client_id' => $this->client
        ];
        if (Task::create($task)) {

            $this->updateMode('show');

            $this->resetInputFields();

            $this->emit('alert', ['type' => 'success', 'message' => 'Task created successfully!']);
        } else {
            $this->emit('alert', ['type' => 'danger', 'message' => 'There is something wrong!, Please try again.']);
        }

    }

    public function updateMode($mode)
    {
        $this->mode = $mode;
    }

    public function archive($taskId)
    {
        $task = Task::find($taskId);
        $task->archive = !$task->archive;
        $task->update();
        $this->emit('alert', ['type' => 'success', 'message' => 'Task updated successfully!']);
    }

    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);
        $task->delete();
        $this->emit('alert', ['type' => 'success', 'message' => 'Task deleted successfully!']);
    }
}
