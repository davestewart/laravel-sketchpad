<?php namespace davestewart\sketchpad\config;

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
            $this->_install  = Paths::folder(base_path('vendor/davestewart/sketchpad/'));
            $this->_storage  = storage_path('sketchpad/');
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
            return Paths::make($this->_install . $path, $relative);
        }

        /**
         * Returns the package path, i.e. vendor/davestewart/sketchpad/package/...
         *
         * @param   string  $path       An optional path to append to the package path
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function package($path = '', $relative = false)
        {
            return Paths::make($this->_install . 'package/' . $path, $relative);
        }

        /**
         * Returns the storage path, i.e. storage/sketchpad/...
         *
         * @param   string  $path       An optional path to append to the storage path
         * @param   bool    $relative   An optional flag to return the path relative to the base path
         * @return  string              The final path
         */
        public function storage($path = '', $relative = false)
        {
            return Paths::make($this->_storage . $path, $relative);
        }


    // ------------------------------------------------------------------------------------------------
    // utilities

		/**
		 * Utility method to create a path; fixing and optionally making relative to the base path
		 *
		 * @param   string  $path       The path to process
		 * @param   bool    $relative   An optional flag to return the path relative to the base path
		 * @return  string              The final path
		 */
		public static function make($path, $relative = false)
		{
		    return $relative
		        ? Paths::relative($path)
		        : Paths::fix($path);
		}

        /**
         * Utility method to make path relative to the base path
         *
         * @param   string  $path       The path to process
         * @return  string              The final path
         */
        public static function relative($path)
        {
            return Paths::fix(str_replace(base_path() . '/', '', $path));
        }

		/**
		 * Utility method to return a path with a trailing slash
		 *
		 * @param   string  $path       The path to process
		 * @param   bool    $relative   An optional flag to return the path relative to the base path
		 * @return  string              The final path
		 */
		public static function folder($path, $relative = false)
		{
		    $path = Paths::make($path, $relative);
		    return rtrim($path, '/') . '/';
		}

		/**
		 * Fixes-up paths:
		 *
		 * - replaces backslashes with forward slashes
		 * - replaces multiple slashes with a single slash
		 *
		 * @param   string  $path       The path to process
		 * @return  string              The fixed path
		 */
		public static function fix($path)
		{
		    $path = str_replace('\\', '/', $path);
		    $path = preg_replace('%/+%', '/', $path);
		    return $path;
		}

}

/**
 * Utility function to build file paths uniformly across OSes
 *
 * @params  string      ...     One or many path segments
 * @return  string              A concatenated string of segments, with backslashes converted to / and double-slashes removed
 */
function path()
{
    return Paths::fix(implode('', func_get_args()));
}
