;(function () {
	function post()
	{
		var height = document.body.clientHeight + 80;
		parent.postMessage({setFrameHeight: height}, "*");
	}

	if (parent.postMessage)
	{
		post();
		window.onresize = post;
	}
}())