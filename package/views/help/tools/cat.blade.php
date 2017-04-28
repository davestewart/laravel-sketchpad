<a href="http://thecatapi.com" target="_blank" id="cat"></a>
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
