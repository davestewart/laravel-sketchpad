<table class="table table-bordered table-striped {{ $class }} debug">
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
			<?php $obj = ! is_scalar($value); ?>
			<td<?php echo $obj ? ' class="pre"' : '' ?>>{{ $obj ? print_r($value, true) : $value }}</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>