<style type="text/css">
	#output .links{ padding: 10px; padding-top:0; border-bottom:1px solid #EEE; margin-bottom:10px; }

	#output .phpinfo h1 {font-size: 150%; margin-top:20px;}
	#output .phpinfo h2 {font-size: 125%;}
	#output .phpinfo h2:first-child {margin-top:20px;}
	#output .phpinfo pre {margin: 0; font-family: monospace;}
	#output .phpinfo hr {background-color: #ccc; border: 0; height: 1px;}
	#output .phpinfo img {float: right; border: 0;}

	#output .phpinfo table a:hover {text-decoration: underline;}
	#output .phpinfo table a:link {color: #009; text-decoration: none; background-color: #fff;}
	#output .phpinfo table {border-collapse: collapse; border: 0; width: 100%; box-shadow: 1px 2px 3px #ccc;}
	#output .phpinfo td, th {border: 1px solid #666 !important; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
	#output .phpinfo .p {text-align: left;}
	#output .phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
	#output .phpinfo .h {background-color: #99c; font-weight: bold;}
	#output .phpinfo .v {background-color: #FBFBFB; max-width: 300px; overflow-x: auto;}
	#output .phpinfo .v i {color: #999; }
	#output .phpinfo .v pre { font-size:10px !important; }
</style>
<div class="links">{!! implode( ' | ', $links)  !!}</div>
<div class="phpinfo">{!! $contents !!}</div>
