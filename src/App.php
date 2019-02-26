<?php

namespace Iidestiny\DependencyInjection;

use InvalidArgumentException;
use ReflectionMethod;

/**
 * Class App
 *
 * @package Iidestiny\DependencyInjection
 * @author  luoyan <iidestiny@vip.qq.com>
 */
class App
{
    /**
     * The instance
     *
     * @var
     */
    protected static $instance;

    /**
     * Instance registered
     *
     * @param $instance
     *
     * @return string
     */
    public static function register($instance)
    {
        if (!is_object($instance)) {
            self::$instance = new $instance();
        } else {
            self::$instance = $instance;
        }

        return new self();
    }

    /**
     * Dependency injector
     *
     * Note that there are type parameters when using and need to pass in the pre-parameters
     * similar to optional parameters and non-optional parameter positions.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function __call($method, $parameters)
    {
        if (!method_exists(self::$instance, $method)) {
            $instance = get_class(self::$instance);

            throw new InvalidArgumentException("Instance [{$instance}] does not exist for [{$method}] method");
        }

        return $this->make($method, ...$parameters);
    }

    /**
     * make method
     *
     * @param       $method
     * @param mixed ...$parameters
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function make($method, ...$parameters)
    {
        $reflector = new ReflectionMethod(self::$instance, $method);

        foreach ($reflector->getParameters() as $key => $parameter) {

            $class = $parameter->getClass();

            if ($class) {
                $param = $parameters[$key] ?? null;

                if (is_object($param) && get_class($param) == $class->name) {
                    continue;
                }

                array_splice($parameters, $key, 0, [
                    new $class->name(),
                ]);
            }
        }

        return call_user_func_array([self::$instance, $method], $parameters);
    }
}