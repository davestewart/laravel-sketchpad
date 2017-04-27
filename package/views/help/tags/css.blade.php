<p>The current method item <code>&lt;li&gt;</code> has a suitably over-the-top class <code>.fancy</code> added to it:</p>
<pre>@css fancy</pre>
<p>It's styled (in part) with the following code in the user <code>styles.css</code> stylesheet (click <a href="javascript:toggleUserStyles()">here</a> to disable it):</p>
<pre class="code css">
li.fancy{
    border:1px solid #333;
    background: repeating-linear-gradient(
        -45deg,
        #222,
        #222 5px,
        #333 5px,
        #333 10px
    );
}</pre>
<p>See the <a href="../basics/userassets">user assets</a> section for more information.</p>
<script>function toggleUserStyles()
{
	var $link = $('link[href$="user/styles.css"]');
	$link.is('[disabled]')
		? $link.removeAttr('disabled')
		: $link.attr('disabled', 'disabled');
}</script>
