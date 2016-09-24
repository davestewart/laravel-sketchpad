<?php namespace davestewart\sketchpad\install;

/**
 * Installer settings
 */
class Settings
{

    // -----------------------------------------------------------------------------------------------------------------
    // PROPERTIES

        public $type;
        public $autoloader;

        public $controllers;
        public $controllersns;
        public $views;
        public $assets;
        public $route;

        public $namespace;
        public $basedir;

        protected $path;

    // -----------------------------------------------------------------------------------------------------------------
    // INSTANTIATION

        public function __construct()
        {
            // paths
            $paths = new Paths();
            $this->path = $paths->storage . 'install.json';
            if( ! file_exists($paths->storage) )
            {
                mkdir($paths->storage, null, true);
            }
            $this->load();
        }


    // -----------------------------------------------------------------------------------------------------------------
    // METHODS

        public function load()
        {
            if(file_exists($this->path))
            {
                // load data
                $json   = file_get_contents($this->path);
                $data   = json_decode($json);

                // populate
                foreach($data as $key => $value)
                {
                    if(property_exists($this, $key))
                    {
                        $this->$key = $value;
                    }
                }
            }
        }

        public function save($input)
        {
            // clean data
            //$input  = array_map(function($value) { return trim($value, ' \\/'); }, $input);

            if(is_string($input['autoloader']))
            {
                $input['autoloader'] = filter_var($input['autoloader'], FILTER_VALIDATE_BOOLEAN);
            }

            // save data
            $json   = json_encode($input, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            return (bool) file_put_contents($this->path, $json);
        }


}