<table class="table table-bordered table-striped {{ $class }} debug">
	<colgroup>
		<col class="col-xs-1">
		<col class="col-xs-7">
	</colgroup>
	<thead>
		<tr>
			<th>#</th>
			@foreach($keys as $key)
			<th>{{ $key }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($values as $index => $obj)
		<tr>
			<th>{{ $index }}</th>
			@foreach($obj as $key => $value)
			<td>{{ is_array($value) || is_object($value) ? print_r($value, true) : $value }}</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>