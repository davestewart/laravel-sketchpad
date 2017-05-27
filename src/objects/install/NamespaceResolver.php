<?php namespace davestewart\sketchpad\objects\install;

/**
 * NamespaceResolver
 *
 * Attempts to resolve the actual namespace of a class using
 * composer PSR4 info and a supplied filename
 */
class NamespaceResolver
{
    protected $namespaces;

    public function __construct($namespaces = null)
    {
        $namespaces
            ? $this->setNamespaces($namespaces)
            : $this->loadNamespaces();
    }

    /**
     * Loads namespaces from composer.json
     *
     * @return $this
     */
    public function loadNamespaces()
    {
        $data       = json_decode(file_get_contents(base_path('composer.json')), JSON_OBJECT_AS_ARRAY);
        $namespaces = array_get($data, 'autoload.psr-4');
        $this->setNamespaces($namespaces);
        return $this;
    }

    /**
     * Sets the source namespace / path array file namespaces will be resolved from
     *
     * @param $namespaces
     * @return mixed
     */
    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
        return $this;
    }

    /**
     * Attempts to get the namespace for a file
     *
     * @param   string      $file               base-relative file path
     * @param   bool        $defaultToPath      flag to use file path as namespace if namespace cannot be matched
     * @return  null|string
     */
    public function getNamespace($file, $defaultToPath = false)
    {
        // massage file path into a format compatible with PSR-4 entries
        $file = str_replace('\\', '/', $file);
        $base = str_replace('\\', '/', base_path()) . '/';
        $file = str_replace($base, '', $file);

        // compare file path against existing entries
        foreach($this->namespaces as $ns => $path)
        {
            // convert paths to all use forward slashes
            $path = str_replace('\\', '/', $path);

            // if the file starts with the namespace path
            if(strpos($file, $path) === 0)
            {
                $file   = substr($file, strlen($path));
                $file   = preg_replace('%/[^/]+$%', '', $file);
                $file   = str_replace('/', '\\', $file);

                // defensive trim, in case any double slashes
                return trim($ns . $file, '\\');
            }
        }

        // namespace could not be resolved
        $info = pathinfo($file);

        return $defaultToPath
            ? str_replace('/', '\\', $info['dirname'])
            : null;

    }
}