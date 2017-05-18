<p>The format of the function is:</p>
<pre class="code php">icon($name, $color = '')</pre>

<p>You can output various combinations of icon and colour, including bootstrap color constants and booleans:</p>


<table class="table">
	<thead>
		<tr>
			<th>Icon</th>
			<th>Code</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data as $datum)
			<tr>
				<td>{!! $datum->icon !!}</td>
				<td><code>{{ $datum->code }}</code></td>
			</tr>
		@endforeach
	</tbody>
</table>

<p>This helper differs from all others in that it <i>returns</i> an HTML string, rather than immediately echoing it, which allows it to be used in other functions:</p>

<pre class="code php">p(icon('bolt') . ' Email');</pre>

<p>The <a href="table">table</a> helper has built-in support for converting columns to icons.</p>