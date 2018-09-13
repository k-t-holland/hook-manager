<?php

namespace KTHolland\HookManager;

abstract class HookModel
{

    /**
     * Name of WordPress hook
     * @var string
     */
    protected $hook;

    /**
     * Object containing callback
     * @var mixed
     */
    protected $component;

    /**
     * Method/function to be fired
     * @var string`
     */
    protected $callback;

    /**
     * set WordPress priority from 1 to 99
     * @var int
     */
    protected $priority;

    /**
     * The number of arguments the callback should expect
     * @var int
     */
    protected $acceptedArgs;

    public function __construct($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        $this->hook         = $hook;
        $this->component    = $component;
        $this->callback     = $callback;
        $this->priority     = $priority ?: 10;
        $this->acceptedArgs = $acceptedArgs ?: 1;
    }

    /**
     * Getter to return properties
     * @param $key
     * @return string
     */
    public function __get($key)
    {
        if($key === 'name'){
            return $this->hook;
        }

        if(property_exists($this, $key)){
            return $this->$key;
        }
    }
}