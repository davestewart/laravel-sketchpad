<?php namespace davestewart\sketchpad\objects\settings;

/**
 * Paths class
 *
 * #END
 */
class Paths
{

    // -----------------------------------------------------------------------------------------------------------------
    // properties

        protected $_install;

        protected $_storage;


    // -----------------------------------------------------------------------------------------------------------------
    // instantiation

        public function __construct()
        {
            $this->_install  = $this->folder(base_path(config('sketchpad.install')));
            $this->_storage  = storage_path('vendor/sketchpad/');
        }


    // ------------------------------------------------------------------------------------------------
    // paths

        /**
         * Returns the install path, i.e. vendor/davestewart/sketchpad/...
         *
         * @param   string  $path       An optional path to append to the base path
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function install($path = '', $relative = false)
        {
            return $this->make($this->_install . $path, $relative);
        }

        /**
         * Returns the publish path, i.e. vendor/davestewart/sketchpad/publish/...
         *
         * @param   string  $path       An optional path to append to the publish path
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function publish($path = '', $relative = false)
        {
            return $this->make($this->_install . 'publish/' . $path, $relative);
        }

        /**
         * Returns the publish path, i.e. storage/vendor/sketchpad/...
         *
         * @param   string  $path       An optional path to append to the storage path
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function storage($path = '', $relative = false)
        {
            return $this->make($this->_storage . $path, $relative);
        }


    // ------------------------------------------------------------------------------------------------
    // utilities

        /**
         * Utility method to create a path; fixing and optionally makeing relative to the base path
         *
         * @param   string  $path       The path to process
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function make($path, $relative = false)
        {
            return $relative
                ? $this->relative($path)
                : $this->fix($path);
        }

        /**
         * Utility method to make path relative to the base path
         *
         * @param   string  $path       The path to process
         * @return  string              The final path
         */
        public function relative($path)
        {
            return $this->fix(str_replace(base_path(), '', $path));
        }

        /**
         * Utility method to return a path with a trailing slash
         *
         * @param   string  $path       The path to process
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function folder($path, $relative = false)
        {
            $path = $this->make($path, $relative);
            return rtrim($path, '/') . '/';
        }

        /**
         * Replaces backslashes with forward slashes
         *
         * @param   string  $path       The path to process
         * @return  string              The fixed path
         */
        public function fix($path)
        {
            return str_replace('\\', '/', $path);
        }

}