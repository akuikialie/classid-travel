<?php
namespace App\Traits;

use ReflectionClass;

trait FragmentRenderer
{
    protected $fragment;
    protected $reflection;
    /**
     * @param $controllerFragment
     * @return $this
     */
    public function fragment($controllerFragment): static
    {
        $this->fragment = $controllerFragment;
        return $this;
    }

    /**
     * @param $method
     * @param ...$args
     * @return void
     * @throws \ReflectionException
     */
    public function render($method, ...$args): void
    {
        try {
            $reflectionMethod = (new ReflectionClass($this->fragment))
                ->getMethod($method);
            $reflectionMethod->setAccessible(true);
            $reflectionMethod->invoke($this->fragment, $args);
        } catch (\ReflectionException $e) {
            throw $e;
        }
    }
}
