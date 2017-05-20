<?php use \davestewart\sketchpad\utils\Html; ?>
<table class="table {{ $class }}" style="{{ $style }}">
	@if($label)
		<caption>{{ $label }}</caption>
	@endif
	<thead>
		<tr>
			@if($index)
			<th style="width:20px">#</th>
			@endif
			@foreach($keys as $x => $key)
			<th style="{{ $cols[$x] }}">{{ $key }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($values as $y => $obj)
		<tr>
			@if($index)
			<th>{{ $y + 1 }}</th>
			@endif
			@foreach($keys as $x => $key)
			<?php
				$row    = (array) $values[$y];
				$value  = array_key_exists($key, $row) ? $row[$key] : null;
				$obj    = ! is_scalar($value);
				$value  = $obj ? print_r($value, true) : $value;
				$class  = $obj || in_array($key, $pre) ? ' class="pre"' : '';
				$isHtml = in_array($key, $html);
				$isIcon = in_array($key, $icon);
			?>
			@if($isIcon)
			<td<?php echo $class ?>>{!! Html::icon($value) !!}</td>
			@elseif($isHtml)
			<td<?php echo $class ?>>{!! Html::getText($value) !!}</td>
			@else
			<td<?php echo $class ?>>{{ Html::getText($value) }}</td>
			@endif
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>