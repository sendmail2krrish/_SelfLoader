<?php

ini_set('display_errors', 1);

require_once __DIR__ . "/../bootstrap/Start.php";

$boot = new _Self\Bootstrap\Start();

print $boot->run();
echo "\n";
