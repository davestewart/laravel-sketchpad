;(function () {
	function post()
	{
		parent.postMessage({setFrameHeight: document.body.clientHeight}, "*");
	}

	if (parent.postMessage)
	{
		post();
		window.onresize = post;
	}
}())