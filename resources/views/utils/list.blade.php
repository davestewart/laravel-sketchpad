<table class="table table-bordered table-striped {{ $class }} debug">
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
		<?php $obj = ! is_scalar($value); ?>
		<tr>
			<th>{{ $key }}</th>
			<td<?php echo $obj ? ' class="pre"' : '' ?>>{{ $obj ? print_r($value, true) : $value }}</td>
		</tr>
		@endforeach
	</tbody>
</table>