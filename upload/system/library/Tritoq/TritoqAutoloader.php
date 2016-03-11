<?php

class TritoqAutoloader
{
    /**
     * Registers Autoloader as an SPL autoloader.
     *
     * @param bool    $prepend Whether to prepend the autoloader or not.
     */
    public static function register($prepend = false)
    {
        if (version_compare(phpversion(), '5.3.0', '>=')) {
            spl_autoload_register(array(__CLASS__, 'autoload'), true, $prepend);
        } else {
            spl_autoload_register(array(__CLASS__, 'autoload'));
        }
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class A class name.
     */
    public static function autoload($class)
    {
        if (0 !== strpos($class, 'Tritoq')) {
            return;
        }

        $file = DIR_SYSTEM . 'library/'.str_replace('\\', '/', $class).'.php';
        if (is_file($file)) {
            require $file;
        }
    }
}
