<?php

namespace App\Http\Livewire;

use App\Agency;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;


class Tasks extends Component
{
    public $tasks, $title, $date, $mode, $client, $updateMode, $taskId, $type, $task_entry, $body, $contact_type;

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
        $modelsMapping = [
            'agency' => 'App\Agency',
            'client' => 'App\Models\Client',
            'lead' => 'App\Models\Lead'
        ];
        $model = $modelsMapping[$this->type];
        $this->tasks = Task::whereHasMorph(
            'Taskable',
            [$model],
            function ($query, $type) use ($client) {
                if ($type === Agency::class) {
                    $query->where('id', $client);
                }
                if ($type === Client::class) {
                    $query->where('id', $client);
                }
                if ($type === Lead::class) {
                    $query->where('id', $client);
                }
            }
        )->get()->sortByDesc('created_at');

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
        $this->body = '';
        $this->contact_type = '';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function createTask()
    {
        $this->validate();

        $modelsMapping = [
            'agency' => 'App\Agency',
            'client' => 'App\Models\Client',
            'lead' => 'App\Models\Lead'
        ];

        if (!array_key_exists($this->type, $modelsMapping)) {
            Session::flash('flash_message_warning', __('Could not create document, type not found! Please contact support'));
            throw new Exception("Could not create comment with type " . $this->type);
            return redirect()->back();
        }

        $model = $modelsMapping[$this->type];
        $source = $model::where('id', '=', $this->client)->first();

        $task = [
            'title' => $this->title,
            'date' => $this->date,
            'body' => $this->body,
            'contact_type' => $this->contact_type,
            'user_id' => Auth::id(),
            'task_entry' => $this->task_entry
        ];

        if ($this->type == 'agency') {
            $task['agency_id'] = $this->client;
        }
        if ($this->type == 'client') {
            $task['client_id'] = $this->client;
        }
        if ($this->type == 'lead') {
            $task['lead_id'] = $this->client;
        }

        $task = $source->tasks()->create($task);

        if ($task) {

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
