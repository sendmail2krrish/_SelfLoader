<?php

namespace _Self\ClassLoader;

/**
 * @author Krishna Paul <sendmail2krrish@gmail.com>
 * @final
 * @package _Self
 */
final class Stack
{
    private static $classes = [];

    public static function run($class)
    {
        self::loader();
        return self::$classes[$class];
    }

    private static function loader()
    {
        self::$classes = json_decode(
                file_get_contents(__DIR__ . "/../bootstrap.json"), true
        );
    }

}