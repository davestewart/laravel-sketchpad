<?php namespace davestewart\sketchpad\objects\route;
use davestewart\sketchpad\objects\reflection\Controller;

/**
 * Call Reference object
 *
 * Extends ControllerReference to manage values needed for a HTTP call to a controller method
 *
 */
class CallReference extends ControllerReference
{
	// ------------------------------------------------------------------------------------------------
	// properties

	    /**
	     * A string method for the called method
	     *
	     * @var string
	     */
	    public $method;

	    /**
	     * An array of values for the called parameters
	     *
	     * @var mixed[]
	     */
	    public $params;


	// ------------------------------------------------------------------------------------------------
	// instantiation

		public static function fromRef($ref)
	    {
	    	$controller = new Controller($ref->abspath, $ref->route);
	        $call = new self($controller->route, $controller->path, $controller->classpath);
	        foreach($controller as $key => $value)
	        {
	            $call->$key = $value;
	        }
	        return $call;
	    }

		public static function fromControllerRef(ControllerReference $controller)
	    {
	        $call = new self($controller->route, $controller->path, $controller->class);
	        foreach($controller as $key => $value)
	        {
	            $call->$key = $value;
	        }
	        return $call;
	    }

	    /**
		 * CallReference constructor
		 *
		 * @param   string  $route
		 * @param   string  $path
		 * @param   string  $class
		 */
		public function __construct($route, $path, $class = null)
		{
			parent::__construct('call', $route, $path);
			$this->class = $class;
		}


	// ------------------------------------------------------------------------------------------------
	// methods

		/**
		 * Determines the method to call
		 *
		 * @param $route
		 * @return $this
		 */
		public function setMethod($route)
		{
			// variables
			$methodUri      = trim(substr($route, strlen($this->route)), '/');
			$segments       = explode(',', $methodUri);

			// properties
			$this->method    = array_shift($segments);

			// return
			return $this;
		}

		/**
		 * Sets the calling parameters from the submitted front end data
		 *
		 * @param   \StdClass[]     $params
		 * @return                  $this
		 */
		public function setParams($params)
		{
			$this->params = [];
			foreach ($params as $param)
			{
				// variables
				$name = $param['name'];
				$type = $param['type'];
				$value = $param['value'];

				// properties
				$this->params[$name] = $this->convert($type, $value);
			}

			// return
			return $this;
		}


	// ------------------------------------------------------------------------------------------------
	// utilities

		/**
		 * Utility used to convert values to the correct type
		 *
		 * @param   string  $type
		 * @param   mixed   $value
		 * @return  mixed
		 */
		protected function convert ($type, $value)
		{
			switch ($type)
			{
				case 'string':
					return (string) $value;

				case 'number':
					return is_float($value)
						? (float) $value
						: (int) $value;

				case 'boolean':
					return $value === true || $value === 'true' || $value === '1' || $value === 'on';

				default:
					if (is_float($value))
					{
						return $this->convert('number', $value);
					}
					if (is_numeric($value))
					{
						return strpos($value, '.') !== FALSE
							? (float) $value
							: (int) $value;
					}
					if ($value === 'true' || $value === 'false')
					{
						return $value === 'true';
					}
					if ($value === '')
					{
						return null;
					}
			}
			return $value;
		}
}

