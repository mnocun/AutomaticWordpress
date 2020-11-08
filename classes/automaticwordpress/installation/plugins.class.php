<?php

namespace AutomaticWordpress\Installation;

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
        if (!is_writable($temporaryLocation) || !extension_loaded('zip')) {
            return false;
        }
        $startExecutionTime = microtime(true);
        foreach ($this->plugins as $name => $url) {
            $archiveLocation = implode(DIRECTORY_SEPARATOR, [$temporaryLocation, "$name.zip"]);
            if (!file_put_contents($archiveLocation, file_get_contents($url))) {
                return false;
            }
            $pluginsLocation = implode(DIRECTORY_SEPARATOR, [$this->location, 'wp-content', 'plugins']);
            if (!$this->installPlugin($archiveLocation, $pluginsLocation)) {
                return false;
            }
        }
        echo 'Plugins installation time: '.(microtime(true) - $startExecutionTime).' s'.PHP_EOL;
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