<?php namespace davestewart\doodle\controllers;

use davestewart\doodle\services\DoodleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Response;

/**
 * Class TestController
 * @package app\Http\Controllers\test
 */
class DoodleController extends Controller
{
	
	/**
	 * @var DoodleService
	 */
	protected $service;

	/**
	 * DoodleController constructor.
	 *
	 * @param DoodleService $service
	 */
	public function __construct(DoodleService $service)
	{
		$this->service = $service;
	}

	public function index()
	{
		return view('doodle::content.index', $this->service->data(''));
	}

	public function view($path)
	{
		return view('doodle::content.index', $this->service->data($path));
	}

	public function get($path = '')
	{
		return Response::json($this->service->getFolder($path));
	}

	public function all($path = '')
	{
		return Response::json($this->service->getFolder($path, true));
	}

	public function call($path)
	{
		return $this->service->call($path);
	}

	public function create(Request $request)
	{
		$input      = $request->all();
		$path       = $input['path'];
		$members    = $input['members'];
		$options    = $input['options'];
		$this->service->create($path, $members, $options);
	}


}

