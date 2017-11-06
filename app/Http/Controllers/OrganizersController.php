<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Organizer;

class OrganizersController extends Controller
{

    public $abilities = [
        'index'      =>      'options_view'  ,
        'show'       =>      'options_view'  ,
        'create'     =>      'options_add'  ,
        'store'      =>      'options_add'  ,
        'edit'       =>      'options_edit'  ,
        'update'     =>      'options_edit'  ,
        'destroy'    =>      'options_delete'  ,
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$organizers = Organizer::all();

		return view('organizers.index', compact('organizers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$departments = Department::all();
        return view('organizers.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate($request, [
            'name' => 'required|between:1,50',
            'type' => 'required|integer',
		]);

		Organizer::create([
            'name' => $request['name'],
            'type' => $request['type'],
			'comments' => $request['comments'],
		]);

		return redirect()->route('organizers.index')->with('alert-class', 'alert-success')->with('message',  __('Added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$organizer = Organizer::find($id);

		return view('organizers.show', compact('organizer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$organizer = Organizer::find($id);
        $departments = Department::all();
		return view('organizers.edit', compact('organizer','departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|between:1,50',
            'type' => 'required|integer',
        ]);

		$organizer = Organizer::find($id);

		if ($organizer)
		{
			$organizer->update([
	            'name' => $request['name'],
                'type' => $request['type'],
                'comments' => $request['comments'],
			]);

			return redirect()->route('organizers.index')->with('alert-class', 'alert-success')->with('message', __('Updated'));
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$organizer = Organizer::find($id);

		if ($organizer)
		{
			$organizer->delete();

			return redirect()->route('organizers.index')->with('alert-class', 'alert-success')->with('message',  __('Deleted'));
		}
    }
}