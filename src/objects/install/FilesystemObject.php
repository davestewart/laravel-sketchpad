<?php namespace davestewart\sketchpad\objects\install;
use davestewart\sketchpad\config\Paths;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Core filesystem class which provides common functionality
 */
abstract class FilesystemObject
{

    protected $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    /**
     * Utility function to return an absolute path from a relative or absolute path
     *
     * @param $path
     * @return string
     */
    protected function makePath($path)
    {
        $path = $this->fs->isAbsolutePath($path)
            ? $path
            : base_path($path);
        return Paths::fix($path);
    }

    protected function isFilename($src)
    {
        return preg_match('%[^/]+\.\w+$%', $src);
    }

    protected function getFileTargetPath($src, $trg)
    {
        // data
        $parts = pathinfo($src);

        // if the src is a file, and the target is a folder, rename the target as a file
        if($this->isFilename($src) && ! $this->isFilename($trg))
        {
            $trg = rtrim($trg, '/') . '/' . $parts['basename'];
        }

        // replace any pathinfo placeholders
        $trg = preg_replace_callback('/\{(dirname|basename|filename|extension)\}/', function($matches) use ($parts)
        {
            return $parts[$matches[1]];
        }, $trg);

        return $trg;
    }

}

