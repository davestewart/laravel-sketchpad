<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Response;

/**
 * Class TestController
 * @package app\Http\Controllers\test
 */
class SketchpadController extends Controller
{
	
	// ------------------------------------------------------------------------------------------------
	// properties
	
		/**
		 * Sketchpad service
		 *
		 * @var Sketchpad
		 */
		protected $sketchpad;
	
	
	// ------------------------------------------------------------------------------------------------
	// instantiation

		/**
		 * SketchpadController constructor.
		 *
		 * @param Sketchpad $sketchpad
		 */
		public function __construct(Sketchpad $sketchpad)
		{
			$this->sketchpad = $sketchpad;
		}
	
	
	// ------------------------------------------------------------------------------------------------
	// public methods

	public function command($type, $data = null)
		{
			$data = $this->sketchpad->getVariables();
	
			return view('sketchpad::pages.' . $type, $data);
		}
	
		public function call($path = '')
		{
			// instantiate setup
			$setup = new Setup();
			
			// return a view
			return $setup->check()
				? $this->sketchpad->call($path)
				: $setup->view();
		}

		public function setup(Request $request)
		{
	
			// config
			$input          = $request->all();
			$config         = config_path('sketchpad.php');
			$contents       = file_exists($config)
								? file_get_contents($config)
								: file_get_contents(base_path('vendor/davestewart/sketchpad/publish/config/config.php'));

			// helper function
			$update = function ($name, $trim) use($input, & $contents)
			{
				$value      = $input[$name];
				$value      = trim($value, '\\/');
				$value      = trim($value, $trim) . $trim;
				$contents   = preg_replace("/('$name'[^']+?)'([^']+?)'/", "$1'$value'", $contents);
			};
	
			// massage input
			$update('route', '/');
			$update('path', '/');
			$update('namespace', '\\');
			$update('assets', '/');
	
			// update double-slashes
			$contents       = str_replace('\\', '\\\\', $contents);
	
			// write the file
			file_put_contents($config, $contents);

			// run the next stage of setup
			return redirect('/' .  $input['route']);
		}
	
		public function create(Request $request)
		{
			// get input
			$input      = $request->all();
			
			// extract variables
			$name       = $input['name'];
			$path       = $input['path'];
			$members    = $input['members'];
			$options    = $input['options'];
	
			// create
		}
	
	
	
	// ------------------------------------------------------------------------------------------------
	// depreciated methods

		public function index()
		{
			return view('sketchpad::index', $this->sketchpad->getData(''));
		}

		public function view($path)
		{
			return view('sketchpad::index', $this->sketchpad->getData($path));
		}
	
		public function get($path = '')
		{
			return Response::json($this->sketchpad->getFolder($path));
		}
	
		public function all($path = '')
		{
			return Response::json($this->sketchpad->getFolder($path, true));
		}
	


}

