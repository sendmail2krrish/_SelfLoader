<?php

/**
 * @author Krishna Paul <sendmail2krrish@gmail.com>
 * @final
 * @package _Self
 */

require_once __DIR__ . "/class-loader/Stack.php";

spl_autoload_register(function ($class)
{
    require_once _Self\ClassLoader\Stack::run($class);
});
