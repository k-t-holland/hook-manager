<?php

namespace KTHolland\HookManager;

use KTHolland\HookManager\Contracts\Hook;

class HookManager
{

    /**
     * WordPress hooks
     * @var array
     */
    protected $hooks = [];

    /**
     * WordPress hooks to be used in unloadRemovableHooks & loadRemoveableHooks
     * @var array
     */
    protected $removable = [];

    /**
     * Return $this->hooks array
     * @return array
     */
    public function getHooks()
    {

        return $this->hooks;

    }

    /**
     * @param string $callback
     * @return bool|mixed
     */
    public function getHook($callback)
    {

        if($this->isHookCallbackNotDefined($callback)){
            return false;
        }

        return $this->hooks[$callback];

    }

    /**
     * @param Hook $hook
     * @param bool $removable
     * @return HookManager
     */
    public function addHook(Hook $hook, $removable = false)
    {

        $hook->add();
        $this->hooks[$hook->callback] = $hook;

        if($removable){
            $this->removable[] = $hook->callback;
        }

        return $this;

    }

    /**
     * @param $callback
     * @return bool
     */
    public function deleteHook($callback)
    {

        if($this->isHookCallbackNotDefined($callback)){
            return false;
        }

        if(isset($this->removable[$callback])){
            unset($this->removable[$callback]);
        }

        $this->hooks[$callback]->remove();
        unset($this->hooks[$callback]);

        return true;

    }

    public function loadAllHooks()
    {

        foreach($this->hooks as $callback => $hook){
            $hook->add();
        }

    }

    public function unloadAllHooks()
    {

        foreach($this->hooks as $callback => $hook){
            $hook->remove();
        }

    }

    public function unloadRemovableHooks()
    {

        foreach($this->removable as $callback){

            if($this->isHookCallbackDefined($callback)){
                $this->hooks[$callback]->remove();
            }

        }

    }

    public function loadRemovableHooks()
    {

        foreach($this->removable as $callback){

            if($this->isHookCallbackDefined($callback)){
                $this->hooks[$callback]->add();
            }

        }

    }

    private function isHookCallbackDefined($callback)
    {

        return isset($this->hooks[$callback]);

    }

    private function isHookCallbackNotDefined($callback)
    {

        return ! $this->isHookCallbackDefined($callback);

    }
}