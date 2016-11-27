<?php namespace davestewart\sketchpad\objects\install;

/**
 * File templating class
 *
 * Takes a source template and target file location
 */
class JSON extends Copier
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
        $text       = file_get_contents($this->src);
        $this->data = json_decode($text, JSON_OBJECT_AS_ARRAY);
        return $this;
    }

    public function set($key, $value)
    {
        array_set($this->data, $key, $value);
        return $this;
    }

    public function get($key)
    {
        return array_get($this->data, $key);
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

}
