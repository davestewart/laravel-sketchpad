<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<meta charset="UTF-8">
	<title>Sketchpad - Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<!-- libs -->
	<link  href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.6/flatly/bootstrap.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.js"></script>

	<!-- sketchpad -->
	<style type="text/css">

		body {
			padding-top: 100px;
			overflow-y: scroll;
			padding-bottom: 100px;
		}

		.navbar-default {
			background-color: #eeeeee;
		}

		.navbar-default .navbar-brand{
			color:#2c3e50 !important;
		}

		#setup{
			width:700px;
			margin:auto;
		}

		h2, h3, h4{
			margin-bottom: 1em;
			margin-top: 1em;
		}

		h4{
			color:black;
			margin-top:2em;
		}

		pre{
			border: none;
			margin: 25px 15px;
			font-size: 12px;
			color: #2c3e50;
			white-space: pre-wrap;
			word-break: break-all;
		}

		code{
			font-size:12px;
		}

	</style>

	<script type="text/javascript">

		function selectText() {
			var range;
			if (document.selection) {
				range = document.body.createTextRange();
				range.moveToElementText(this);
				range.select();
			} else if (window.getSelection) {
				range = document.createRange();
				range.selectNode(this);
				window.getSelection().addRange(range);
			}
		}

		jQuery(function(){ jQuery('pre').on('click', selectText); });

	</script>

</head>
<body data-mode="home">

	<div class="navbar navbar-default navbar-fixed-top">

		<div class="container">
			<div class="navbar-header">
				<span class="navbar-brand">Sketchpad</span>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">

			<div id="setup">

				@yield('content')

			</div>

		</div>
	</div>

</body>
</html>

