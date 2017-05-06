<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Iframe</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Oswald:300,400|Pacifico" rel="stylesheet">
	<style>

		body {
			padding:30px;
			background: #9cb8b3;
			font-family: 'Oswald', cursive, sans-serif;
			font-weight: 300;
			color: #4e5c59;
		}

		h1 {
			font: 600 1.5em/1 'Pacifico', sans-serif;
			margin: 0 0 0 0;
			padding: 0;
		}

		h1 span {
			letter-spacing: .5em;
		}

		h1 span,
		h1 span:after {
			font-weight: 900;
			color: #efedce;
			white-space: nowrap;
			display: inline-block;
			position: relative;
			letter-spacing: .1em;
			padding: .2em 0 .25em 0;
		}

		h1 span {
			font-size: 4em;
			z-index: 100;
			text-shadow: .04em .04em 0 #9cb8b3;
		}

		h1 span:after {
			content: attr(data-shadow-text);
			color: rgba(0,0,0,.35);
			text-shadow: none;
			position: absolute;
			left: .0875em;
			top: .0875em;
			z-index: -1;
			-webkit-mask-image: url(http://f.cl.ly/items/1t1C0W3y040g1J172r3h/mask.png);
		}

		p {
			font-size: 1.1em;
		}

		pre {
			display: block;
			padding: 10px;
			word-break: break-all;
			word-wrap: break-word;
			background: rgba(0, 0, 0, 0.1);
			border-radius: 4px;
			font: normal 11px/1.4em Menlo,Monaco,Consolas,Courier New,monospace!important;
			white-space: pre;
			color: #efedce;
			margin: 20px;
		}

	</style>
</head>
<body>
	<main>

		<h1>Use an <span data-shadow-text="@iframe">@iframe</span> to separate content...</h1>
	</main>

	<p>Sketchpad loads content inline by default, but for pages already with styling or head content, this ain't gonna fly.</p>
	<p>Consider using iframes when:</p>
	<ul>
		<li>running anything that would break Sketchpad's layout or scripting</li>
		<li>loading your application's main controllers</li>
		<li>loading content from external sites</li>
	</ul>
	<p>To automatically size any iframes to the size of their content (and so remove scrollbars) include the following script at the end of the loaded page:</p>
	<pre>&lt;script src="{{ $route }}assets/js/iframe.js">&lt;/script></pre>
	<script src="{{ $route }}assets/js/iframe.js"></script>
</body>
</html>

