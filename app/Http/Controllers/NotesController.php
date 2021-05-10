<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $note = Note::create([
            'body' => $request->body,
            'client_id' => $request->client_id,
            'date' => now(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('clients.edit', $request->client_id)
            ->with('toast_success', __('Created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        try {
            $note->delete();
            Session()->flash('toast_success', __('Note successfully deleted'));
        } catch (\Exception $e) {
            Session()->flash('toast_warning', __('Note could not be deleted, contact for support'));
        }

        return redirect()->back();
    }

    public function ajax()
    {

        $id = request('id');

        $note = Note::where('id', $id)->first();
        if (!$note) {
            $body = '';
        } else {
            $body = $note->body;
        }

        //return json_encode($body);
        return response($body);
    }
}
