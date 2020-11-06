<?php

namespace AutomaticWordpress;
use Exception;

define('ABS', __DIR__);

require implode(DIRECTORY_SEPARATOR, [ABS, 'autoload.php']);

try {
    $startExecutionTime = microtime(true);

    if (!isset($argv[0])) {
        // Called by browser
        echo '<pre>';
    }

    if (!isset($argv[1])) {
        throw new Exception('The script requires a location');
    }

    $installationName = $argv[1];
    $lang = Lang::resolveLang($argv[2]);

    $configuration = new Configuration(implode(DIRECTORY_SEPARATOR, [ABS, '.env']));
    $database = new Database($configuration);
    $wordpress = new Wordpress($database, $configuration);
    
    if (!$wordpress->install($installationName, Lang::EN)) {
        throw new Exception('Cannot install wordpress', 20);
    } else {
        throw new Exception('Wordpress has been installed successfully', 0);
    }
} catch (Exception $exception) {
    echo $exception->getMessage().PHP_EOL;
    echo 'Execution time: '.round(microtime(true) - $startExecutionTime, 2).' s'.PHP_EOL;
}