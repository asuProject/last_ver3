@extends('layouts.base')

@section('title', __('foreigner attendance'))

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
<? $i=0; ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>{{__('#')}}</th>
			<th>{{ __('conference name') }}</th>
			<th>{{ __('name') }}</th>
			<th>{{ __('country') }}</th>
			<th>{{ __('city') }}</th>
			<th>{{ __('Options') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($attendance as $i=>$attendee)
			<tr>
				<td>{{++$i}}</td>
				<td>{{$attendee->Conference->name}}</td>
				<td>{{$attendee->name}}</td>
				<td>{{$attendee->Country->name}}</td>
				<td>{{$attendee->City->name}}</td>
				<td><a href="{{ route('attendee.edit' , $attendee->id) }}"><span class="glyphicon glyphicon-edit"></span></a>
					<div style="display: inline-block;">
						<form action="{{ route('attendee.destroy',$conference->id) }}" method="post" onsubmit="return confirm('{{__('Delete a conference Confirmation')}}')">
						{{csrf_field()}}
						{{ method_field('DELETE') }}
						<button type="submit" class="btn btn-danger btn-xs icon-button"><span class="glyphicon glyphicon-remove"></span></button>
						</form>
					</div>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
	
@endsection
