<p>Type something below and submit the form back to the same URL:</p>

<!-- any form with an empty or missing "action" attribute will be intercepted and submitted by sketchpad -->
<form class="form form-inline">
	<input  class="form-control input-sm" type="text" name="text" value="" placeholder="Type something here...">
	<button class="btn btn-primary btn-sm" type="submit">Submit</button>
</form>

@if($form)
<hr />
<p>All you need to do is check for <code>Sketchpad::$form</code> in your methods and take action appropriately:</p>
{!! dump($form) !!}
@endif