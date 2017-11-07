<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Conference;
use App\ConferenceRole;
use App\ForeignerAttendee;
use App\ForeignAttendance;

class ForeignAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$attendance = ForeignAttendance::all();

		return view('attendance.foreign.attendance.index', compact('attendance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$conferences = Conference::all();
		$attendees = ForeignerAttendee::all();
		$roles = ConferenceRole::all();

		return view('attendance.foreign.attendance.create', compact('conferences', 'attendees', 'roles'));
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
			'conference_id' => 'required|integer|min:1|exists:conferences,id',
			'attendee_id' => 'required|integer|min:1|exists:foreign_attendees,id',
			'role_id' => 'required|integer|min:1|exists:conference_roles,id'
		]);

		$attendee = ForeignerAttendee::find($request['attendee_id']);
		$attendee->Conference()->attach($request['conference_id'], ['role_id' => $request['role_id']]);

		return redirect()->route('attendance.foreign.attendance.index')->with('alert-class', 'alert-success')->with('message', 'Done!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($attendee_id, $conference_id)
    {
		$attendee = ForeignerAttendee::find($attendee_id);
		$conference = Conference::find($conference_id);

		if ($attendee && $conference)
		{
			$role_id = $attendee->Conference()->where('id', '=', $conference->id)->first()->pivot->role_id;

			if ($role_id) {
				$role = ConferenceRole::find($role_id);

				return view('attendance.foreign.attendance.show', compact('attendee', 'conference', 'role'));
			}
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($attendee_id, $conference_id)
    {
		$conferences = Conference::all();
		$roles = ConferenceRole::all();
		$attendee = ForeignerAttendee::find($attendee_id);
		$conference = Conference::find($conference_id);

		if ($attendee && $conference)
		{
			$role_id = $attendee->Conference()->where('id', '=', $conference->id)->first()->pivot->role_id;

			if ($role_id) {
				$role = ConferenceRole::find($role_id);

				return view('attendance.foreign.attendance.edit', compact('conferences', 'roles', 'attendee', 'conference', 'role'));
			}
		}
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
			'old_conference_id' => 'required|integer|min:1|exists:conferences,id',
			'conference_id' => 'required|integer|min:1|exists:conferences,id',
			'role_id' => 'required|integer|min:1|exists:conference_roles,id'
		]);

		$attendee = ForeignAttendee::find($id);

		if ($attendee)
		{
			$attendee->Conference()->detach($attendee['old_conference_id']);
			$attendee->Conference()->attach($request['conference_id'], ['role_id' => $request['role_id']]);

			return redirect()->route('attendance.foreign.attendance.index')->with('alert-class', 'alert-success')->with('message', 'Done!');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
		$this->validate($request, [
			'conference_id' => 'required|integer|min:1|exists:conferences,id'
		]);

		$attendee = ForeignAttendee::find($id);

		if ($attendee)
		{
			$attendee->Conference()->detach($attendee['conference_id']);

			return redirect()->route('attendance.foreign.attendance.index')->with('alert-class', 'alert-success')->with('message', 'Done!');
		}
    }

        public function addinput(){

        $i = $this->input->post('i');

        echo '
        <div class="form-group row">
            <label class="col-md-4 control-label">'. __("conference name") .'</label>
            <div class="col-md-8">
                <select name="conference_id['.$i.']" required>
                <option value="" disabled selected>'. __("choose conference") .'</option>';
                foreach($conferences as $conference):
                   echo ' <option value="'.$conference->id.'">'.$conference->name.'</option>';
                endforeach;
            echo '</select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-4 control-label">'. __("attendee name") .'</label>
            <div class="col-md-8">
                <select name="attendee_id['.$i.']" required>
                <option value="" disabled selected>'. __("choose attendee") .'</option>';
                foreach($attendees as $attendee):
                   echo ' <option value="'.$attendee->id.'">'.$attendee->name.'</option>';
                endforeach;
            echo '</select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-4 control-label"> '.__("country") .'</label>
            <div class="col-md-3">
            <select name="country_id['.$i.']" required  onchange="get_cities(this.value)"">
                <option value="" disabled selected>'. __("choose country") .'</option>';
                foreach($countries as $country):
                    echo '<option value="'.$country->id.'">'.$country->name.'</option>';
                endforeach;
            echo '</select>
            </div>
            <label class="col-md-2 control-label">'. __("city") .'</label>
            <div class="col-md-3">
            <select name="city_id['.$i.']" required id="city">
                <option value="">'. __("Please select a country") .'</option>
            </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-4 control-label">'. __("conference role") .'</label>
            <div class="col-md-8">
            <select id="role_id" name="role_id['.$i.']" multiple>
                <option value="" disabled>'. __("choose role") .'</option>';
                foreach($roles as $role):
                    echo '<option value="{{$role->id}}">'.$role->role.'</option>';
                endforeach;
            echo '</select>
            </div>
        </div>';
    
        }


}