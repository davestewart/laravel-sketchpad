<?php namespace davestewart\sketchpad\objects\install;

/**
 * File templating class
 *
 * Takes a source template and target file location
 *
 * @property \StdClass $data
 */
class JSON extends Copier implements \JsonSerializable
{

    protected $data;

    /**
     * JSON constructor
     *
     * @param $src
     * @param null $trg
     */
    public function __construct($src, $trg = null)
    {
        parent::__construct($src, $trg ? $trg : $src);
        $this->load();
    }

    public function load()
    {
        if(file_exists($this->src))
        {
            $text       = file_get_contents($this->src);
            $this->data = json_decode($text, JSON_OBJECT_AS_ARRAY);
            $error      = json_last_error();

            if ($error !== JSON_ERROR_NONE)
            {
                switch ($error) {
                    case JSON_ERROR_DEPTH:
                        $error = 'Maximum stack depth exceeded';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $error = 'Underflow or the modes mismatch';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $error = 'Unexpected control character found';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $error = 'Malformed JSON';
                        break;
                    case JSON_ERROR_UTF8:
                        $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                        break;
                    default:
                        $error = 'Unknown error';
                        break;
                }
                throw new \Exception("$error in '{$this->src}'");
            }
        }

        return $this;
    }

	public function __get($name)
	{
		if($name === 'data')
		{
			return $this->data;
		}
    }

    public function set($key, $value = null)
    {
    	if (is_string($key))
	    {
	        array_set($this->data, $key, $value);
	    }
        return $this;
    }

    public function get($key = '', $default = null)
    {
    	return array_get($this->data, $key, $default);
    }

    public function has($key)
    {
        return array_has($this->data, $key);
    }

    public function create()
    {
        $text       = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return (bool) file_put_contents($this->trg, $text);
    }

	function jsonSerialize()
	{
		return $this->data;
	}
}
