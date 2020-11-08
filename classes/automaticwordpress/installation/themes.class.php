<?php

namespace AutomaticWordpress\Installation;

use AutomaticWordpress\Console;
use ZipArchive;

class Themes
{
    protected $location;
    protected $themes;

    public function __construct(string $location, array $themes)
    {
        $this->location = $location;
        $this->themes = $themes;
    }

    public function install(string $temporaryLocation) : bool
    {
        Console::centerEcho('Start installing themes', 50, Console::TEXT_REVERSE);
        if (!is_writable($temporaryLocation) || !extension_loaded('zip')) {
            return false;
        }
        $startExecutionTime = microtime(true);
        foreach ($this->themes as $name => $url) {
            Console::centerEcho("Installing the \"$name\" theme");
            $archiveLocation = implode(DIRECTORY_SEPARATOR, [$temporaryLocation, "$name.zip"]);
            $archiveContent = @file_get_contents($url);
            if ($archiveContent === false) {
                Console::centerEcho("Could not find theme \"$name\"", 50, Console::COLOR_RED);
                continue;
            }
            if (!file_put_contents($archiveLocation, $archiveContent)) {
                return false;
            }
            $themesLocation = implode(DIRECTORY_SEPARATOR, [$this->location, 'wp-content', 'themes']);
            if (!$this->installPlugin($archiveLocation, $themesLocation)) {
                return false;
            }
        }
        Console::centerEcho('Themes installation time: '.round(microtime(true) - $startExecutionTime, 2).' s');
        return true;
    }

    protected function installTheme(string $archiveLocation, string $location) : bool
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