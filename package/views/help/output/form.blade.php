<p>Any form with an empty or missing "action" attribute will be intercepted and submitted by sketchpad:</p>
<pre>&lt;form action=""&gt;&lt;/form&gt;</pre>

<p>You then check the <code>Sketchpad::$form</code> variable in your methods and take action appropriately:</p>
<pre class="code php">public function form()
{
    if (Sketchpad::$form)
    {
        foreach (Sketchpad::$form as $key => $value) { ... }
    }
}</pre>

<hr />

<p>Submit the following test form to see it loop back to the same URL:</p>

<form class="form form-inline">
	<input  class="form-control input-sm" type="text" name="text" value="" placeholder="Type something here...">
	<button class="btn btn-primary btn-sm" type="submit">Submit</button>
</form>

@if($form)
<p>The form data is:</p>
{!! dump($form) !!}
@else
<p>Waiting for form data...</p>
@endif