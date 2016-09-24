<?php namespace davestewart\sketchpad\install;

/**
 * Paths class
 *
 * #END
 */
class Paths
{

    // -----------------------------------------------------------------------------------------------------------------
    // PROPERTIES

        public $publish;

        public $storage;

        public $config;


    // -----------------------------------------------------------------------------------------------------------------
    // INSTANTIATION

        public function __construct()
        {
            $this->publish  = base_path('vendor/davestewart/sketchpad/publish/');
            $this->storage  = storage_path('vendor/sketchpad/');
            $this->config   = config_path('sketchpad.php');
        }

}