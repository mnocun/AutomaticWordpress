<?php

namespace AutomaticWordpress\Installation;

use AutomaticWordpress\Console;
use ZipArchive;

class Plugins
{
    protected $location;
    protected $plugins;

    public function __construct(string $location, array $plugins)
    {
        $this->location = $location;
        $this->plugins = $plugins;
    }

    public function install(string $temporaryLocation) : bool
    {
        if (empty($this->plugins)) {
            Console::centerEcho('No plugins have been defined to install', 50, Console::TEXT_REVERSE);
            return true;
        }
        Console::centerEcho('Start installing plugins', 50, Console::TEXT_REVERSE);
        if (!is_writable($temporaryLocation) || !extension_loaded('zip')) {
            return false;
        }
        $startExecutionTime = microtime(true);
        foreach ($this->plugins as $name => $url) {
            Console::centerEcho("Installing the \"$name\" plugin");
            $archiveLocation = implode(DIRECTORY_SEPARATOR, [$temporaryLocation, "$name.zip"]);
            $archiveContent = @file_get_contents($url);
            if ($archiveContent === false) {
                Console::centerEcho("Could not find plugin \"$name\"", 50, Console::COLOR_RED);
                continue;
            }
            if (!file_put_contents($archiveLocation, $archiveContent)) {
                return false;
            }
            $pluginsLocation = implode(DIRECTORY_SEPARATOR, [$this->location, 'wp-content', 'plugins']);
            if (!$this->installPlugin($archiveLocation, $pluginsLocation)) {
                return false;
            }
        }
        Console::centerEcho('Plugins installation time: '.round(microtime(true) - $startExecutionTime, 2).' s', 50, Console::COLOR_LIGHT_BLUE, false);
        Console::echo('', Console::BG_DEFAULT);
        return true;
    }

    protected function installPlugin(string $archiveLocation, string $location) : bool
    {
        $archive = new ZipArchive;
        if (!$archive->open($archiveLocation)) {
            unlink($archiveLocation);
            return false;
        }
        $archive->extractTo($location);
        if (!$archive->close()) {
            echo $archive->getStatusString().PHP_EOL;
        }
        unlink($archiveLocation);
        return true;
    }

}