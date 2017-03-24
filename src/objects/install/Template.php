<?php namespace davestewart\sketchpad\objects\install;

/**
 * File templating class
 *
 * Takes a source template and target file location
 */
class Template extends Copier
{

    public $text;

    /**
     * Template constructor.
     *
     * @param $src
     * @param null $trg
     * @param null $data
     */
    public function __construct($src, $trg, $data = null)
    {
        parent::__construct($src, $trg);
        $this->load();
        if($data)
        {
            $this->set($data);
        }
    }

    public function load()
    {
        $this->text = file_get_contents($this->src);
        return $this->text;
    }

    public function set($data)
    {
        $data   = (array) $data;
        $text   = $this->text;
        foreach($data as $key => $value)
        {
            $text = str_replace('%' . $key . '%', $value, $text);
        }
        $this->text = $text;
        return $this;
    }

    /**
     * Populates and writes the template to the target file
     *
     * @return bool
     */
    public function create()
    {
        return (bool) $this->fs->dumpFile($this->trg, $this->text);
    }

}