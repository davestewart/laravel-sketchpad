<?php namespace davestewart\sketchpad\objects\install;
use ReflectionClass;

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

    protected $classname;

    protected $namespace;

    public function __construct($src, $trg, $data = null)
    {
        // parent
        parent::__construct($src, $trg, $data);

        // classname
        preg_match('%([^/]+).php$%', $this->trg, $matches);
        $this->classname = $matches[1];

        // namespace
        $this->setNamespace();
    }

    public function setNamespace()
    {
        if( ! $this->resolver )
        {
            $this->resolver = new NamespaceResolver();
        }
        $this->resolver->loadNamespaces();
        $this->namespace = $this->resolver->getNamespace($this->trg, true);
        $data =
        [
            'namespace' => $this->namespace
        ];
        $this->set($data);
        return $this;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function loads()
    {
        $classpath = $this->namespace . '\\' . $this->classname;
        try
        {
            new ReflectionClass($classpath);
            return true;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

}