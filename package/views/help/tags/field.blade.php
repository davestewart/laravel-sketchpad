<h3>Overview</h3>
<p>You can override Sketchpad's choice of <a href="../methods/typecasting">input field</a> with various HTML 5 element or input field types using the <code>@field</code> tag:</p>
<pre>
@field  select    $select     options:One=1,Two=2,Three=3
@field  number    $range      min:0|max:100|step:5
@field  date      $date
@field  color     $color
</pre>

<p>The output from the fields above can be seen here:</p>
{!! dump($params) !!}

<h3>Tag signature</h3>

<p>The <code>@field</code> tag signature matches the <code>@param</code> tag signature, making it easy to pair the two up in your DocBlocks:</p>
<pre>
@param  type      $param      text
@field  type      $param      attributes
</pre>
<p>The <code>value</code> and <code>type</code> are derived from your method signature and <code>@param</code> tag, with any specific UI requirements drawn from the <code>@field</code> tag.</p>
<p>The format allows you to specify <strong>form element</strong> or <strong>input type</strong>, and optionally pass <strong>HTML attributes</strong>:</p>
<pre>
@field  date        $date
@field  color       $color
@field  number      $value      min:0|max:100|step:5
@field  select      $select     options:One=1,Two=2,Three=3
@field  datalist    $select     options:foo,bar,baz
</pre>

<p>The values are converted directly into HTML attributes:</p>
<pre>
&lt;input type="number" value="" min="0" max="100" step="5"&gt;
</pre>

<h3>Supported input types</h3>
<p>The following element / input <code>types</code> are supported:</p>

<style type="text/css">
	#table select,
	#table input {
		width:100%;
	}
	#table select {
		height: 23px;
	}
</style>
<table class="table table-bordered table-striped data" id="table">
	<thead>
	<tr>
		<th style="width:100px">type</th>
		<th style="width:350px">element</th>
		<th style="width:250px">output</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>select</td>
		<td>HTML <code>select</code> element</td>
		<td>
			<select>
				<option>One</option>
				<option>Two</option>
				<option>Three</option>
			</select></td>
	</tr>
	<tr>
		<td>datalist</td>
		<td>HTML 5 <code>datalist</code> element</td>
		<td>
			<input type="text" list="sample">
			<datalist id="sample">
				<option>foo</option>
				<option>bar</option>
				<option>baz</option>
			</datalist>
		</td>
	</tr>
	<tr><td>text</td><td>HTML <code>text</code> input</td><td><input type="text"></td></tr>
	<tr><td>number</td><td>HTML 5 <code>number</code> input</td><td><input type="number"></td></tr>
	<tr><td>color</td><td>HTML 5 <code>color</code> input</td><td><input type="color"></td></tr>
	<tr><td>date</td><td>HTML 5 <code>date</code> input</td><td><input type="date"></td></tr>
	<tr><td>datetime</td><td>HTML 5 <code>datetime-local</code> input</td><td><input type="datetime-local"></td></tr>
	<tr><td>time</td><td>HTML 5 <code>time</code> input</td><td><input type="time"></td></tr>
	<tr><td>week</td><td>HTML 5 <code>week</code> input</td><td><input type="week"></td></tr>
	<tr><td>month</td><td>HTML 5 <code>month</code> input</td><td><input type="month"></td></tr>
	</tbody>
</table>

<p>For <code>select</code> and <code>dataset</code> elements, ensure you pass an <code>options</code> value as outlined above.</p>
<p>Note that some HTML 5 input types (such as <code>url</code> and <code>email</code>) are not supported, as they are used primarily for validation, which is not yet supported in Sketchpad.</p>

<h3>Attributes syntax</h3>
<p>The attribute syntax is similar to the Laravel validation syntax, converting values to HTML attributes or options:</p>
<pre>
@field  type     $varname      value:0|values:1,2,3|options:One=1,Two=2,Three=3
</pre>

<p>Note the order of splitting / grouping operators for attributes:</p>
{!! tb($splits, 'html:operator,grouping,example|cols:100,400,200') !!}


<h3>Supported input attributes</h3>
<p>As mentioned above, attribute <code>name:value</code> pairs are converted directly into HTML <code>name="value"</code> attributes, so you can pass whatever is relavent to the element's spec.</p>
<p>See the <a href="https://www.w3schools.com/html/html_form_input_types.asp" target="_blank">W3 Schools</a> page for supported values for each element or input type.</p>

<h3>Other options</h3>
<p>If your input requirements are any more complex than this, consider using <a href="../output/forms">forms</a> within your methods.</p>