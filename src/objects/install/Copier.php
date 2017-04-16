<?php namespace davestewart\sketchpad\objects\install;

/**
 * Copies files or folders from one directory to another
 */
class Copier extends FilesystemObject
{
    public $src;
    public $trg;

    public function __construct($src, $trg = null)
    {
        parent::__construct();
        $this->src = $this->makePath($src);
        $this->trg = $trg
            ? $this->getFileTargetPath($src, $this->makePath($trg))
            : null;
    }

    public function create()
    {
        $this->copy($this->trg);
    }

    public function copy($trg)
    {
        if (is_dir($this->src))
        {
            $this->fs->mirror($this->src, $trg);
        }
        else
        {
            $this->fs->copy($this->src, $trg);
        }
    }

    public function move($trg)
    {

    }

    public function exists()
    {
        return file_exists($this->trg);
    }

	public function remove()
	{
		$this->fs->remove($this->trg);
    }

}