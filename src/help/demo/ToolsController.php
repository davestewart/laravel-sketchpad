<?php namespace davestewart\sketchpad\help\demo;

use davestewart\sketchpad\config\SketchpadSettings;

/**
 * Apart from just testing code, you can use Sketchpad as front end to access often-used tools and functions
 *
 * @package App\Http\Controllers
 */
class ToolsController
{
	public function index()
	{
		md(__DIR__ . '/tools.md');
	}

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
		$users = \DB::table('users')
			->select('id', 'name', 'email', 'created_at')
			->limit(1)
			->paginate(15);

		if($users)
		{
			$data = $users->getCollection();
			if (!empty($data))
			{
				tb($data);
				echo $users;
				return;
			}
		}
		p('Unable to show users');

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

		vue('sketchpad::help.tools.routes', $array);
	}

	/**
	 * Browse your local filesystem
	 *
	 * @param string $path
	 * @return View|string
	 */
    public function browseFilesystem($path = '')
    {
        // helpers
        function getBreadcrumbs($base, $path)
        {
            $paths  = ['/' => $base];
            $path   = trim($path, '/');
            if ($path !== '')
            {
                $segments   = explode('/', $path);
                $current    = '/';
	            foreach($segments as $segment)
	            {
	                $current .= $segment . '/';
	                $paths[$current] = $segment;
	            }
            }
            return $paths;
        }

	    // paths
	    $path       = str_replace('../', '', $path);
	    $path       = '/' . trim(preg_replace('%[\\/]+%', '/', $path), '/');
        $realpath   = realpath(base_path($path));
	    $base       = pathinfo(base_path())['basename'];

        // found
        if($realpath)
        {
            try
            {
                $objects        = array_diff(scandir($realpath), ['.','..']);
                $breadcrumbs    = getBreadcrumbs($base, $path);
                $folders        = array_filter($objects, function($f) use ($realpath) { return is_dir($realpath . '/' . $f); });
                $files          = array_filter($objects, function($f) use ($realpath) { return is_file($realpath . '/' . $f); });
                $path           = rtrim($path, '/') . '/';
                $parent         = $path !== '/' ? preg_replace('%[^/]+/$%', '', $path) : '/';

                return view('sketchpad::help.tools.folder', compact('parent', 'path', 'folders', 'files', 'breadcrumbs'));
            }
            catch(\Exception $e)
            {
                return "Unable to read folder '$path'";
            }
        }

        // not found
        return "Path '$path' not found";
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
     * @param   int     $section    The section to show
     * @field   select  $section    options:All=-1,General=1,Credits=2,Configuration=4,Modules=8,Environment=16,Variables=32,License=64
     */
	public function phpInfo($section = -1)
	{
		ob_start();
		phpinfo($section);
		$contents = ob_get_contents();
		ob_end_clean();

		$contents   = str_replace('<hr />', '', $contents);
		$contents   = preg_replace('/^[\s\S]+?body>/', '', $contents);
		$contents   = preg_replace('/<\/body>[\s\S]+$/', '', $contents);

        return view('sketchpad::help.tools.phpinfo', compact('contents'));
	}


    /**
     * Just for fun!
     *
     * @group Other
     */
	public function randomCat()
    {
    	return view('sketchpad::help.tools.cat');
    }

}
