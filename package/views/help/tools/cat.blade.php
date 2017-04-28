<a href="http://thecatapi.com" target="_blank" id="cat">
	<div>
		<img src="{{ $route }}assets/images/ajax-loader-bars.gif" alt="">
	</div>
</a>

<style>
	#cat div {
		position: relative;
		width: 100%;
		padding-top: 66.66%;
		border: 1px solid #EEE;
		background: #F9F9F9;
	}

	#cat div img {
		position: absolute;
		left: 50%;
		top: 50%;
		margin: -8px 0 0 -8px;
	}
</style>

<script>
	function loadImage()
	{
		var img = new Image();
		var src = 'http://thecatapi.com/api/images/get?format=src&rand=' + Math.floor(Math.random() * 1000);
		img.onerror = loadImage
		img.onload = function ()
		{
			document.getElementById('cat').innerHTML = '<img style="width:100%" src="' + src + '">';
		}
		img.src = src;
	}
	loadImage();
</script>
