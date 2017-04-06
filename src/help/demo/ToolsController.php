<?php namespace davestewart\sketchpad\help\demo;

use App\data\entities\User;
use davestewart\sketchpad\config\SketchpadSettings;
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
	 *
	 * @group Application
	 */
	public function dumpApp()
	{
		dump(app());
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
		$users = null;
		foreach($classes as $class)
		{
			if(class_exists($class))
			{
				p("Using user class <code>$class</code> and fetching users...");
				$users = $class::all();
				break;
			}
		}

		if($users)
		{
			$data = $users->toArray();
			return count($data)
				? tb($data)
				: p('There are no users in the database', 'note');
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
			// properties
			$methods = method_exists($route, 'getMethods') ? $route->getMethods() : $route->methods;
			$uri = method_exists($route, 'getUri') ? $route->getUri() : $route->uri;
			$action = $route->getAction()['uses'];

			// data
			$array[] =
				[
					'methods'   => implode('|', $methods),
					'uri'       => $uri,
					'name'      => $route->getName(),
					'action'    => $action instanceof \Closure ? 'Closure' : $action,
					'middleware'=> implode(', ', $route->middleware()),
				];
		}

		vue('sketchpad::help.vue.routes', $array);
	}

    /**
     * Browse your local filesystem
     *
     * @param $path
     * @return View|string
     */
    public function browseFilesystem($path = '')
    {

        // parameters
        if( ! $path )
        {
            $path = base_path($path);
        }

        // paths
        $_path      = $path;
        $path       = realpath($path);
        $parent     = $path == '' ? null : realpath($path . '/../');

        // found
        if($path)
        {
            function getBreadcrumbs($path)
            {
                $segments   = array_flip(explode('/', $path));
                $lastPath   = '';
                foreach($segments as $key => $value)
                {
                    $path = $lastPath . $key . '/';
                    $segments[$key] = $path;
                    $lastPath = $path;
                }
                return $segments;
            }

            try
            {
                $objects        = array_diff(scandir($path), ['.','..']);
                $breadcrumbs    = getBreadcrumbs($path);
                $folders        = array_filter($objects, function($f) use ($path) { return is_dir($path . '/' . $f); });
                $files          = array_filter($objects, function($f) use ($path) { return is_file($path . '/' . $f); });
                $path           = rtrim($path, '/') . '/';

                return view('sketchpad::help.folder', compact('parent', 'path', 'folders', 'files', 'breadcrumbs'));
            }
            catch(\Exception $e)
            {
                return "Unable to read folder '$path'";
            }
        }

        // not found
        return "Path '$_path' not found";
	}

	/**
	 * See what's in the session
	 *
	 * @group Environment
	 */
	public function viewSession()
	{
		ls(\Session::all(), true);
	}

	/**
	 * Check your sketchpad settings
	 */
	public function sketchpadSettings(SketchpadSettings $settings)
	{
		return $settings;
	}

    /**
     * Output the result of `phpinfo()`
     *
     * Note the use of escaping into HTML to output the style tag
     * @param string $key
     */
	public function phpInfo($key = 'all')
	{
		$sections =
		[
			'all' => -1,
			'general' => 1,
			'credits' => 2,
			'configuration' => 4,
			'modules' => 8,
			'environment' => 16,
			'variables' => 32,
			'license' => 64,
		];
		$section = isset($sections[$key])
			? $sections[$key]
			: -1;

        $links = [];
        foreach($sections as $key => $value)
        {
            $links[] = '<a href="?key=' .$key. '">' .$key. '</a> ';
        }

		?>
		<style type="text/css">
            #output .links{ padding: 10px; padding-top:0; border-bottom:1px solid #EEE; margin-bottom:10px; }

			#output h1 {font-size: 150%;}
			#output h2 {font-size: 125%;}
			#output pre {margin: 0; font-family: monospace;}
			#output hr {background-color: #ccc; border: 0; height: 1px;}
			#output img {float: right; border: 0;}

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
		<?php

		ob_start();
		phpinfo($section);
		$contents = ob_get_contents();
		ob_end_clean();

		$contents   = preg_replace('/^[\s\S]+?body>/', '', $contents);
		$contents   = preg_replace('/<\/body>[\s\S]+$/', '', $contents);
        echo '<div class="links">' . implode( ' | ', $links) . '</div>';
        echo '<div class="phpinfo">' . $contents . '</div>';
	}


    /**
     * Just for fun!
     *
     * @group Other
     */
	public function randomCat()
    {
        return '<a href="http://thecatapi.com"><img style="width:100%" src="http://thecatapi.com/api/images/get?format=src&rand=' .rand(0, 1000). '"></a>';
    }

}