<?php

namespace App\Http\Livewire;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notes extends Component
{
    public $notes, $body_note, $mode, $client, $updateMode, $noteId, $notePin;

    protected $rules = [
        'body_note' => 'required',
    ];

    public function mount($client)
    {
        $this->mode = 'show';
        $this->notes = Note::all();
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
        $this->notes = Note::whereHas('client', function ($query) use ($client) {
            $query->where('client_id', $client);
        })->get()->sortByDesc('date')->sortByDesc('favorite');
        return view('clients.notes.index');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    private function resetInputFields()
    {
        $this->body_note = '';
        $this->notePin = false;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function createNote()
    {
      $this->validate();


      $note = [
          'body' => $this->body_note,
          'favorite' => $this->notePin,
          'date' => now(),
          'user_id' => Auth::id(),
          'client_id' => $this->client
      ];


      if (Note::create($note)) {

          $this->updateMode('show');

          $this->resetInputFields();

          $this->emit('alert', ['type' => 'success', 'message' => 'Note created successfully!']);
      } else {
          $this->emit('alert', ['type' => 'danger', 'message' => 'There is something wrong!, Please try again.']);
      }
    }

    public function pinNote($noteId)
    {
        $note = Note::find($noteId);
        $note->favorite = !$note->favorite;
        $note->update();
        $this->emit('alert', ['type' => 'success', 'message' => 'Note add to favory']);
    }

    public function updateMode($mode)
    {
        $this->mode = $mode;
    }

    public function deleteNote($noteId)
    {
        $note = Note::findOrFail($noteId);
        $note->delete();
        $this->emit('alert', ['type' => 'success', 'message' => 'Note deleted successfully!']);

    }
}
