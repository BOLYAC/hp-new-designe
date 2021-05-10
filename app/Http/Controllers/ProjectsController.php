<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProjectsController extends Controller
{
    private $projects;

    public function __construct()
    {
        $json = Http::get('https://hashimproperty.com/wp-json/wp/v2/properties')
        ->json();
        $this->projects = $json;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|JsonResponse|View
     */
    public function index()
    {
        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        dd($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProject()
    {
        return DataTables::of($this->projects)->make();
        //return json_encode($this->projects, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    public function getSingleProject($id)
    {

      $project = Http::get('https://hashimproperty.com/wp-json/wp/v2/properties/' . $id)
      ->json();
      $project = collect($project);
      //dd($project);
      $image = Http::get('https://hashimproperty.com/wp-json/wp/v2/media/' . $project['featured_media'])->json();
      $images = [];
      foreach($project['property_meta']['fave_property_images'] as  $key => $projMedia) {

          $i = Http::get('https://hashimproperty.com/wp-json/wp/v2/media/' . $projMedia)->json();

          $images[] = $i['source_url'];

      }

      return view('projects.show', compact('project', 'image', 'images'));
    }
}
