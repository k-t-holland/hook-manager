<?php

namespace KTHolland\HookManager;

use KTHolland\HookManager\Contracts\Hook;

class ActionHook extends HookModel implements Hook
{

    public function __construct($hook, $component, $callback, $priority = null, $acceptedArgs = null)
    {

        parent::__construct($hook, $component, $callback, $priority, $acceptedArgs);

    }

    /**
     * Perform WordPress add_action()
     */
    public function add()
    {

        add_action($this->hook, [$this->component, $this->callback], $this->priority, $this->acceptedArgs);

    }

    /**
     * Perform WordPress remove_action()
     */
    public function remove($priority = 99){

        remove_action($this->hook, [$this->component, $this->callback], $priority);

    }

}