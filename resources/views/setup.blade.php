<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<meta charset="UTF-8">
	<title>Sketchpad - Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- fonts -->
    <link  href="{{ $assets }}fonts/lato/lato.css" rel="stylesheet">
    <link  href="{{ $assets }}fonts/font-awesome/font-awesome.min.css" rel="stylesheet">

    <!-- libs -->
	<script src="{{ $assets }}lib.js"></script>
	<link  href="{{ $assets }}lib.css" rel="stylesheet">

    <!-- app -->
	<link  href="{{ $assets }}app.css" rel="stylesheet">
	<link  href="{{ $assets }}setup.css" rel="stylesheet">

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

	<script type="application/json" id="settings">
{!! json_encode($settings, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
	</script>

</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<span class="navbar-brand">Sketchpad</span>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<setup></setup>
		</div>
	</div>

	<script src="{{ $assets }}app.js"></script>

</body>
</html>

