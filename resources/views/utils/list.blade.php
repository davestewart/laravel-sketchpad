<table class="table table-bordered {{ $class }} debug ">
	<colgroup>
		<col class="col-xs-1">
		<col class="col-xs-7">
	</colgroup>
	<thead>
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
		@foreach($values as $key => $value)
		<tr>
			<th>{{ $key }}</th>
			<td>{{ is_array($value) || is_object($value) ? print_r($value, true) : $value }}</td>
		</tr>
		@endforeach
	</tbody>
</table>