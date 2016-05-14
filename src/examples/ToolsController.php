<?php namespace davestewart\sketchpad\examples;

use App\data\entities\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;

/**
 * Apart from just testing code, you can use Sketchpad as front end to access often-used tools and functions
 *
 * @package App\Http\Controllers
 */
class ToolsController extends Controller
{

	/**
	 * View the app state
	 */
	public function dumpApp()
	{
		dump(app());
	}
	
	/**
	 * See what's in the session
	 *
	 */
	public function viewSession()
	{
		ls(\Session::all(), true);
	}

	/**
	 * Output the result of phpinfo()
	 *
	 * Note the use of escaping into HTML to output the style tag
	 */
	public function phpInfo($key = 'all')
	{

		$sections =
			[
				'general' => 1,
				'credits' => 2,
				'configuration' => 4,
				'modules' => 8,
				'environment' => 16,
				'variables' => 32,
				'license' => 64,
				'all' => -1,
			];
		$section = isset($sections[$key])
			? $sections[$key]
			: -1;

		?>
		<style type="text/css">
			#output pre {margin: 0; font-family: monospace;}
			#output a:link {color: #009; text-decoration: none; background-color: #fff;}
			#output a:hover {text-decoration: underline;}
			#output table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
			#output .center {text-align: center;}
			#output .center th {text-align: center !important;}
			#output td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
			#output h1 {font-size: 150%;}
			#output h2 {font-size: 125%;}
			#output .p {text-align: left;}
			#output .e {background-color: #ccf; width: 300px; font-weight: bold;}
			#output .h {background-color: #99c; font-weight: bold;}
			#output .v {background-color: #ddd; max-width: 300px; overflow-x: auto;}
			#output .v i {color: #999;}
			#output img {float: right; border: 0;}
			#output hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
		</style>
		<?php

		ob_start();
		phpinfo($section);
		$contents = ob_get_contents();
		ob_end_clean();

		$contents = preg_replace('/^[\s\S]+?body>/', '', $contents);
		$contents = preg_replace('/<\/body>[\s\S]+$/', '', $contents);
		echo $contents;

	}

	/**
	 * Show users in a table
	 */
	public function viewUsers()
	{
		$classes =
		[
			'\User',
			'\App\User',
			'\App\Models\User',
			'\App\Models\Entities\User',
			'\App\data\entities\User',
		];
		$data = null;
		foreach($classes as $class)
		{
			if(class_exists($class))
			{
				p("Instantiating user class '$class' and fetching users...");
				$data = $class::all();
				break;
			}
		}
		if($data)
		{
			tb($data->toArray());
		}
		else
		{
			p('Could not find User class in the following array:');
			pr($classes);
		}

	}

	/**
	 * Example tool with a Vue version of the `artisan route:list` command, plus filtering functionality
	 */
	public function viewRoutes()
	{
		// variables
		$routes = \Route::getRoutes();
		$array  = [];
		foreach ($routes as /** @var Route */ $route)
		{
			$action = $route->getAction()['uses'];
			$array[] =
			[
				'method'    => implode('|', $route->getMethods()),
				'uri'       => $route->getUri(),
				'name'      => $route->getName(),
				'action'    => $action instanceof \Closure ? 'Closure' : $action,
				'middleware'=> implode(', ', $route->middleware()),
			];
		}

		echo vue('sketchpad::examples.vue.routes', ['data' => $array]);
	}


	protected function cat($type = '')
	{
		return $this->curl('thecatapi.com/api/images/get?format=html&type=' . $type);
	}


	protected function curl($url)
	{
		$ch		= curl_init('http://' . $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}



}