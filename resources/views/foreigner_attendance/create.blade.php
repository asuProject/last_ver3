@extends('layouts.base')
@section('title', __('Add attendance'))
@section('contents')

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
<script type="text/javascript">
$(function() {

    var addDiv = $('#addinput');
    var i = $('#addinput p').size() + 1;
	var url = '{!! route("ForeignAttendance.addinput") !!}';

    $('#addNew').click(function(e){
       e.preventDefault();
              $.post(url,{'_token': $('meta[name=csrf-token]').attr('content'), 'i': i },function(data){
                $(addDiv).append(data);
                i++;
                return false;
        });
    });
    });
</script>

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

	<form class="form-horizontal" id="create_form" method="post" action="{{ route('') }}">
		{{ csrf_field() }}

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('conference name') }}</label>
			<div class="col-md-8">
				<select name="conference_id[0]" required>
				<option value="" disabled selected>{{ __('choose conference') }}</option>
				@foreach($conferences as $conference)
					<option value="{{$conference->id}}">{{$conference->name}}</option>
				@endforeach
			</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('attendee name') }}</label>
			<div class="col-md-8">
				<select name="attendee_id[0]" required>
				<option value="" disabled selected>{{ __('choose attendee') }}</option>
				@foreach($attendees as $attendee)
					<option value="{{$attendee->id}}">{{$attendee->name}}</option>
				@endforeach
			</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('country') }}</label>
			<div class="col-md-3">
			<select name="country_id[0]" required  onchange='get_cities(this.value)'>
				<option value="" disabled selected>{{ __('choose country') }}</option>
				@foreach($countries as $country)
					<option value="{{$country->id}}">{{$country->name}}</option>
				@endforeach
			</select>
			</div>
			<label class="col-md-2 control-label">{{ __('city') }}</label>
			<div class="col-md-3">
			<select name="city_id[0]" required id="city">
				<option value="">{{ __('Please select a country') }}</option>
			</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-4 control-label">{{ __('conference role') }}</label>
			<div class="col-md-8">
			<select id="role_id" name="role_id[0]" multiple>
				<option value="" disabled>{{ __('choose role') }}</option>
				@foreach($roles as $role)
					<option value="{{$role->id}}">{{$role->role}}</option>
				@endforeach
			</select>
			</div>
		</div>


  <div id="addinput" class="row"><p>
  <center>
  	<button type="button" class="btn btn-default btn-lg">
      <a href="#" class="button radius" id="addNew"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ __('add input') }}</a>
    </button>
	</center>
</p>
  </div>

		<button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
	</form>
	
@endsection
