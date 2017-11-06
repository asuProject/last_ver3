@extends('layouts.base')

@section('title', __('Edit attendance'))

@section('contents')

	@if (count($errors) > 0)
		<div class="alert">
			<div class="alert alert-warning">
				<h4>Errors:</h4>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif


<SCRIPT LANGUAGE="JavaScript">
function get_cities(country_id){
			var options = '';
			var url = '{!! route('town.ajax.get') !!}';
			var city = $('#city');

      $.post(url,
        {'_token': $('meta[name=csrf-token]').attr('content'),
        'country_id': country_id},
        function(data){
				 	var towns = data.towns;
				 	$.each(towns , function(index,value){
				 		var option = '<option value="'+value.id+'">'+value.name+'</option>';
				 		options += option;
				 	});
				 	city.empty().html(options);
        });
  }
  </SCRIPT>

	<form class="form-horizontal" id="create_form" method="post" action="{{ route('conference.update',$conference->id) }}">
		{{ csrf_field() }}
		{{ method_field('PATCH') }}
		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('conference name') }}</label>
			<div class="col-md-8">
				<select name="conference_id" required>
				<option value="" disabled selected>{{ __('choose conference') }}</option>
				@foreach($conferences as $conference)
					<option value="{{$conference->id}}" 
					@if($conference->id == $attendee->conference_id) selected @endif>{{$conference->name}}</option>
				@endforeach
			</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('attendee name') }}</label>
			<div class="col-md-8">
				<select name="attendee_id" required>
				<option value="" disabled selected>{{ __('choose attendee') }}</option>
				@foreach($attendees as $attende)
					<option value="{{$attendee->id}}" 
					@if($attendee->id == $attende->attendee_id) selected @endif>{{$attendee->name}}</option>
				@endforeach
			</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('country') }}</label>
			<div class="col-md-3">
			<select name="country_id" required  onchange='get_cities(this.value)'>
				<option value="">{{ __('choose country') }}</option>
				@foreach($countries as $country)
					<option value="{{$country->id}}" @if($country->id == $conference->country_id) selected @endif>
					{{$country->name}}</option>
				@endforeach
			</select>
			</div>
			<label class="col-md-2 control-label">{{ __('city') }}</label>
			<div class="col-md-3">
			<select name="city_id" required id="city">
				<option value="">{{ __('choose city') }}</option>
				@foreach($cities as $city)
					<option value="{{$city->id}}" 
					@if($city->id == $conference->city_id) selected @endif>{{$city->name}}</option>
				@endforeach
			</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('conference role') }}</label>
			<div class="col-md-8">
			<select id="role_id" name="role_id[]" multiple>
				<option value="" disabled>{{ __('choose role') }}</option>
				@foreach($roles as $role)
					<option value="{{$role->id}}"
					@if($role->id == $attende->role_id) selected @endif>{{$role->role}}</option>
				@endforeach
			</select>
			</div>
		</div>

		<button type="submit" class="btn btn-primary">{{ __('Edit') }}</button>
	</form>
	
@endsection
