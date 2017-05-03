
<p>Your method's parameter types (<code>string</code>, <code>boolean</code>, etc) determine the HTML input field types.</p>
<pre class="code php">
public function typeCasting($string = 'hello', $number = 1, $boolean = true, $mixed = null)
{
    // do something with parameters
}
</pre>

<p>They also enable Sketchpad to cast submitted values <em>back</em> to the expected type; no need for type-juggling in your methods:</p>
{!! vd($params) !!}

<p>Should you need to override determined types, you can either <strong>type-hint</strong> your DocBocks:</p>
<pre>
@param  string     $string     This is a text field
@param  int        $number     This is a number field
@param  boolean    $boolean    This is a checkbox
@param  mixed      $mixed      This is a text field (but will be converted to the correct type)
</pre>

<p>Or, use an additional <code>@field</code> tag to provide more information to the front end:</p>
<pre>
@param  string     $string     Say hello in a chosen language
@field  select     $string     options:hello,greetings,bonjour,hola,namaste

@param  int        $number     Pick a number in a range
@field  number     $number     min:-10|max:10
</pre>

<p>Click <a href="../tags/field">here</a> for further information about <code>@field</code>.</p>