<?php namespace davestewart\sketchpad\controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

use davestewart\sketchpad\config\SketchpadSettings;
use davestewart\sketchpad\config\SketchpadConfig;
use davestewart\sketchpad\services\Sketchpad;

/**
 * Class SketchpadController
 *
 * @private
 */
class ApiController extends Controller
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
	// endpoints

        /**
         * Run a controller method
         *
		 * @param   Request     $request
		 * @param   string      $path
		 * @return  \Illuminate\View\View|mixed|string
		 */
		public function run(Request $request, $path = '')
		{
			// data
			$data   = $request->get('_data', []);
			if (is_string($data))
			{
				$data = json_decode($data, JSON_OBJECT_AS_ARRAY);
			}

			// form
			$form   = $request->get('_form', null);
			parse_str($form, $form);

			// run
            return $this->sketchpad->run($path, $data, $form);
		}

		/**
		 * Returns the JSON for a single controller
		 *
		 * @param null $route
		 * @return mixed
		 * @internal param Request $request
		 * @internal param $path
		 */
        public function load($route = null)
        {
            return response()->json($this->sketchpad->getController($route));
		}

		/**
		 * Loads custom page content
		 *
		 * @param   string  $name
		 * @return  string
		 */
		public function page($name)
		{
			return view("sketchpad::$name");
		}

		/**
		 * Loads or saves settings data
		 *
		 * @method  POST
		 * @method  GET
		 * @param   Request $request
		 * @param   SketchpadConfig $config
		 * @return  SketchpadSettings
		 */
		public function settings(Request $request, SketchpadConfig $config)
		{
			function textToArray ($text)
			{
				return array_values(array_filter(array_map('trim', explode("\n", trim($text))), 'strlen'));
			}

			if($request->isMethod('post'))
			{
				// convert data
				$data = json_decode($request->get('settings'));

				// trim values
				$data->site->assets = textToArray($data->site->assets);
				$data->livereload->paths = textToArray($data->livereload->paths);

				// save
				$config->settings->save((array) $data);
			}
			return response()->json($config->settings);
		}

		/**
		 * Validates existence of a path
		 *
		 * @method  GET
		 * @param   Request $request
		 * @return  array
		 */
		public function path(Request $request)
		{
			$relpath = $request->get('path');
			$abspath = base_path($relpath);
			return [
				'relpath' => $relpath,
				'abspath' => $abspath,
				'exists' => file_exists($abspath)
			];
		}

}

require_once __DIR__ . '/../utils/helpers.php';
