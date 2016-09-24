<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<meta charset="UTF-8">
	<title>Sketchpad - Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- libs -->
	<link  href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.6/flatly/bootstrap.min.css" rel="stylesheet">
	<link  href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.js"></script>

    <style type="text/css">
        {!! $styles !!}
    </style>

	<!-- sketchpad -->
	<style type="text/css">

        /** form **/

        fieldset {
            margin-bottom: 1.5em; }

        legend {
            margin-bottom: 0.3em; }

        .inline label.radio {
            padding-top: 0; }

        .inline div.radio {
            display: inline-block;
            margin-right: 10px; }

        pre,
        .table td.pre,
        .table.pre td,
        .code {
            font: normal 11px/1.4em Menlo, Monaco, Consolas, "Courier New", monospace !important;
            white-space: pre;
            color: #555;
            margin: 20px; }

        pre {
            background-color: #FBFBFB; }


        /** controls **/


        body {
            overflow-y: scroll;
            padding-bottom: 100px; }

        body.modal-open .navbar-fixed-top {
            margin-right: 15px; }

        h1, h2, h3, h4, h5, h6 {
            color: #2c3e50; }

        h1:first-child,
        h2:first-child,
        h3:first-child,
        h4:first-child {
            margin-top: 0; }

        h3:not(:first-child),
        h4:not(:first-child) {
            margin-top: 1.5em; }

        h1 {
            font-size: 60px; }

        code {
            font-size: 0.8em; }

        form label {
            padding: 4px; }

        form textarea {
            max-width: 100%; }

        form .help-text,
        form .help-block {
            font-size: 0.8em;
            padding-left: 4px; }

        form .form-group-submit {
            margin-top: 30px;
            margin-bottom: 0;  }

        form .form-group:last-child {
            margin-bottom: 0; }

        .form-control {
            background-color: rgba(220, 228, 236, 0.4);
            border: none;
            padding: 9px 10px;
            font-size: inherit;
            height: 40px; }

        /** formatting **/

        body {
			padding-top: 100px;
			overflow-y: scroll;
			margin-bottom: 600px;
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

        .form-group .help-block.hint,
        .form-group.active .help-block.prompt{
            display:none;
        }

        .form-group.active .help-block.hint,
        .form-group .help-block.prompt{
            display:block;
        }

        .help-block{
            line-height:18px;
        }

        .help-block.hint{
            color:black;
            font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
            font-size: 0.7em;
        }

        .form-group.has-error .help-block.hint{
            color:#C00;
        }


        /** LOGS **/

        .logs{
            padding:10px 20px;
        }

        .logs .log{
            position: relative;
            list-style: none;
        }

        .logs .log .state{
            text-align:center;
            width:2%;
        }

        .logs p{
            margin-bottom:0.2em;
        }

        .logs .log .help-block{
            font-size:0.8em;
        }

        .logs .log.pass i{
            color:#0C0;
        }

        .logs .log.fail i{
            color:#C00;
        }

        .logs code{
            font-size: 0.7em;
            line-height: 1em;
            word-break: break-word;
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

	<script type="application/json" id="settings">
		{!! json_encode($settings, JSON_UNESCAPED_SLASHES) !!}
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

			<setup></setup>

		</div>
	</div>



	<script>{!! $script !!}</script>

</body>
</html>

