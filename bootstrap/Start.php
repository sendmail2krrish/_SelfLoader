<?php

namespace _Self\Bootstrap;

/**
 * @author Krishna Paul <sendmail2krrish@gmail.com>
 * @final
 * @package _Self
 */
class Start
{
    private $classes = [];

    public function run()
    {
        $this->loadPhpFile(__DIR__ . "/../../../" . $this->loadConfig());

        $bootstrap = \fopen(__DIR__ . "/../bootstrap.json", "w") or die("Unable to open file!");
        fwrite($bootstrap, json_encode($this->classes));
        fclose($bootstrap);

        return json_encode($this->classes);
    }

    private function loadConfig()
    {
        return json_decode(
            file_get_contents(__DIR__ . "/../config.json"),
            true
        )['root'];
    }

    private function loadPhpFile($directory)
    {
        if (is_dir($directory))
        {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]);
            foreach ($scan as $file)
            {
                if (is_dir($directory . "/" . $file))
                {
                    $this->loadPhpFile($directory . "/" . $file);
                }
                else
                {
                    if (pathinfo($directory . '/' . $file, PATHINFO_EXTENSION) === "php")
                    {
                        $cn = $this->getClassFromFile($directory . '/' . $file);
                        if ($cn)
                        {
                            $this->classes[$cn] = realpath($directory . '/' . $file);
                        }
                    }
                }
            }
        }
    }

    private function getClassFromFile($path)
    {
        $contents          = file_get_contents($path);
        $namespace         = $class             = "";
        $getting_namespace = $getting_class     = false;

        foreach (token_get_all($contents) as $token)
        {
            if (is_array($token) && $token[0] == T_NAMESPACE)
            {
                $getting_namespace = true;
            }
            if (is_array($token) && in_array($token[0], [T_CLASS, T_NAMESPACE, T_TRAIT]))
            {
                $getting_class = true;
            }
            if ($getting_namespace === true)
            {
                if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR]))
                {
                    $namespace .= $token[1];
                }
                else if ($token === ';')
                {
                    $getting_namespace = false;
                }
            }
            if ($getting_class === true)
            {
                if (is_array($token) && $token[0] == T_STRING)
                {
                    $class = $token[1];
                    break;
                }
            }
        }
        return $namespace ? $namespace . '\\' . $class : $class;
    }

}