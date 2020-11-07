<?php

namespace AutomaticWordpress;
use Exception;

define('ABS', __DIR__);

require implode(DIRECTORY_SEPARATOR, [ABS, 'autoload.php']);

try {
    $startExecutionTime = microtime(true);

    $console = new Console($argv ?? null);
    if (!$console->isConsole()) {
        echo '<pre>';
        throw new Exception('The script cannot be called by the browser yet', 0);
    }

    $profiles = new Profiles(implode(DIRECTORY_SEPARATOR, [ABS, 'profiles.ini']));
    $profile = $profiles->getProfile($console->getFlag('profile'));
    if (is_null($profile)) {
        $profile = Profile::createEmpty();
    }

    if ($console->hasFlag('lang')) {
        $profile->setLang($console->getFlag('lang'));
    }

    $installationName = $console->getLocation();

    $configuration = new Configuration(implode(DIRECTORY_SEPARATOR, [ABS, '.env']));
    
    $database = new Database($configuration);
    $wordpress = new Wordpress($database, $configuration);
    
    if (!$wordpress->install($installationName, $profile)) {
        throw new Exception('Cannot install wordpress', 20);
    } else {
        throw new Exception('Wordpress has been installed successfully', 0);
    }
} catch (Exception $exception) {
    echo $exception->getMessage().PHP_EOL;
    echo 'Execution time: '.round(microtime(true) - $startExecutionTime, 2).' s'.PHP_EOL;
}