<?php namespace davestewart\sketchpad\objects\install;

/**
 * ClassTemplate
 *
 * Creates classes from text templates
 */
class ClassTemplate extends Template
{

    /**
     * Namespace resolver
     *
     * Used to resolve namespaces for files based on current composer setup
     *
     * @var NamespaceResolver $resolver
     */
    protected $resolver;

    public function __construct($src, $trg, $data = null)
    {
        parent::__construct($src, $trg, $data);
    }

    public function loadNamespaces()
    {
        $resolver = new NamespaceResolver();
        $resolver->loadNamespaces();
        $data =
        [
            'namespace' => $resolver->getNamespace($this->trg)
        ];
        $this->set($data);
        return $this;
    }

}