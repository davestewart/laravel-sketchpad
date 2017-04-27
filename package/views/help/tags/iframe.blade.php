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

	</style>
</head>
<body>
	<h1>Use <strong class="red">@iframe</strong> to separate content</h1>
	<p>Sketchpad loads content inline by default, but sometimes this is not what you want.</p>
	<p>Consider using iframes when:</p>
	<ul>
		<li>running anything that would break Sketchpad's layout or scripting</li>
		<li>loading your application's main controllers</li>
		<li>loading content from external sites</li>
	</ul>
	<script src="/sketchpad/assets/js/iframe.js"></script>
</body>
</html>

