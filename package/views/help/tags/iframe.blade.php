<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Iframe</title>
	<style>
		@import url(https://fonts.googleapis.com/css?family=Cardo|Lato:700,400,400italic);

		@import url(https://fonts.googleapis.com/css?family=Fira+Sans:700|Lato:300,300italic,700);

		body > * {
			margin-top: 0;
		}

		h1,h2,h3,h4,h5,h6 {
			font-family: "Fira Sans", sans-serif;
			font-weight: 700;
		}
		p,a,li,blockquote {
			font-family: "Lato", sans-serif;
			font-weight: 400;
		}

		.red {
			color: red;
		}

		body {
			padding:20px;
			line-height: 1.4em;
		}

		pre {
			display: block;
			padding: 10px;
			word-break: break-all;
			word-wrap: break-word;
			background-color: #fbfbfb;
			border-radius: 4px;
			font: normal 11px/1.4em Menlo,Monaco,Consolas,Courier New,monospace!important;
			white-space: pre;
			color: #000;
			margin: 20px;
		}

	</style>
</head>
<body>
	<h1>Use <strong class="red">@iframe</strong> to separate content</h1>
	<p>Sketchpad loads content inline by default, but sometimes this is not what you want (in this case, loading a custom font).</p>
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

