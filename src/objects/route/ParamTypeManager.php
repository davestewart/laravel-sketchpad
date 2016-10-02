<?php namespace davestewart\sketchpad\objects\route;

use davestewart\sketchpad\objects\reflection\Controller;
use Session;

/**
 * Utility class to save, load and parse controller and method parameter types
 */
class ParamTypeManager
{

    // ------------------------------------------------------------------------------------------------
    // instantiation

    public static function create()
    {
        return new self;
    }


    // ------------------------------------------------------------------------------------------------
    // session

    /**
     * Saves an array of controllers' parameter types to the session
     *
     * @param   Controller[] $controllers
     */
    public function saveAll($controllers)
    {
        $types = [];
        foreach ($controllers as $controller) {
            $types = array_merge($types, $this->get($controller));
        }
        Session::forget('sketchpad.types');
        Session::put('sketchpad.types', $types);
    }

    /**
     * Saves a single controller's parameter types to the session
     *
     * @param   Controller $controller
     */
    public function saveOne($controller)
    {
        $types = Session::get('sketchpad.types');
        $types = array_merge($types, $this->get($controller));
        Session::put('sketchpad.types', $types);
    }

    /**
     * Loads (if teh route exists) a parameter array if saved in the session
     *
     * @param $route
     * @return array
     */
    public function loadOne($route)
    {
        return Session::get('sketchpad.types.' . $route);
    }


    // ------------------------------------------------------------------------------------------------
    // objects

    /**
     * Gets a single controller's parameter types as a parameter => type associative array
     *
     * @param   Controller $controller
     * @return  array
     */
    public function get($controller)
    {
        $types = [];
        foreach ($controller->methods as $method) {
            $params = [];
            foreach ($method->params as $param) {
                $params[$param->name] = $param->type;
            }
            if ($params) {
                $types[$method->route] = $params;
            }
        }
        return $types;
    }

    /**
     * Converts an array of parameters to the correct type
     *
     * Works by loading any saved array from the session, and converting
     *
     * @param   string $route
     * @param   array $params
     * @return  array
     */
    public function convert($route, $params)
    {
        $types = $this->loadOne($route);
        if ($types) {
            foreach ($params as $name => $value) {
                $type = $types[$name];
                if ($type == 'number') {
                    $params[$name] = (float)$value;
                } else if ($type == 'boolean') {
                    $params[$name] = $value === 'true';
                } else if ($type == 'mixed') {
                    $params[$name] = $this->cast($value);
                } else if ($type == 'null' && $value == 'null') {
                    $params[$name] = null;
                }
            }
        }

        return $params;
    }

    /**
     * Parses an array of parameters to the correct type
     *
     * Works by guessing the types from the values
     *
     * @param   mixed[] $params
     * @return  mixed[]
     */
    protected function parse($params)
    {
        foreach ($params as $name => $value) {
            $params[$name] = $this->cast($value);
        }
        return $params;
    }

    /**
     * Casts a string value to its appropriate type
     *
     * Works by guessing the types from the values
     *
     * @param   string $value
     * @return  mixed
     */
    protected function cast($value)
    {
        if (is_numeric($value)) {
            return (float)$value;
        } else if ($value === 'true' || $value === 'false') {
            return $value === 'true';
        }
        return $value;
    }

}