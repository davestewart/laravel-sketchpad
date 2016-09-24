<?php namespace davestewart\sketchpad\install\objects;

/**
 * Single folder object
 */
class Folder extends FilesystemObject
{

    public $src;

    public function __construct($src)
    {
        parent::__construct();
        $this->src  = $this->makePath($src);
    }

    public function create()
    {
        $this->fs->mkdir($this->src);
    }

    public function exists()
    {
        return file_exists($this->src);
    }

}