<?php namespace davestewart\sketchpad\controllers;

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
	
	/**
	 * Sketchpad service
	 *
	 * @var Sketchpad
	 */
	protected $sketchpad;

	/**
	 * SketchpadController constructor.
	 *
	 * @param Sketchpad $sketchpad
	 */
	public function __construct(Sketchpad $sketchpad)
	{
		$this->sketchpad = $sketchpad;
	}

	public function command($type, $data = null)
	{
		$data = $this->sketchpad->getVariables();

		return view('sketchpad::pages.' . $type, $data);
	}

	public function call($path = '')
	{
		return $this->sketchpad->call($path);
	}

	public function create(Request $request)
	{
		$input      = $request->all();
		$name       = $input['name'];
		$path       = $input['path'];
		$members    = $input['members'];
		$options    = $input['options'];

		$this->sketchpad->create($path, $name, $members, $options);
	}




	public function index()
	{
		return view('sketchpad::content.index', $this->sketchpad->getData(''));
	}

	public function view($path)
	{
		return view('sketchpad::content.index', $this->sketchpad->getData($path));
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

