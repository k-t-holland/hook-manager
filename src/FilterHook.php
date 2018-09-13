<?php

namespace KTHolland\HookManager;

use KTHolland\HookManager\Contracts\Hook;

class FilterHook extends HookModel implements Hook
{

    public function __construct($hook, $component, $callback, $priority = null, $acceptedArgs = null)
    {

        parent::__construct($hook, $component, $callback, $priority, $acceptedArgs);

    }

    /**
     * Perform WordPress add_filter()
     */
    public function add()
    {

        add_filter($this->hook, [$this->component, $this->callback], $this->priority, $this->acceptedArgs);

    }

    /**
     * Perform WordPress remove_filter()
     */
    public function remove($priority = 99){

        remove_filter($this->hook, [$this->component, $this->callback], $priority);

    }

}