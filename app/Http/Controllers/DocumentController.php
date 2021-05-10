<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$this->validate($request, [
            'title' => ['required'],
            'full' => ['required']
        ]);*/
        $data = $request->all();
        $client = Client::where('id', $request->client_id)->pluck('full_name');
        if ($request->hasFile('full')) {
            $imagePath = $data['full']->store('clients/' . $client[0], 'public');
        }
        $data['user_id'] = Auth::id();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $data['full'] = $imagePath;

        ClientDocument::create($data);

        return redirect()->back()
            ->with('toast_success', 'File add successfully');
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
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientDocument = ClientDocument::findOrFail($id);
        try {
            $clientDocument->delete();
            Session()->flash('toast_message', __('File successfully deleted'));
        } catch (\Exception $e) {
            Session()->flash('toast_warning', __('File could not be deleted, contact for support'));
        }

        return redirect()->back();
    }
}
