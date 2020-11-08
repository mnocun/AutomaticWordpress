<?php

namespace AutomaticWordpress;

use Exception;

class Profile
{
    public $name;
    public $lang;
    public $plugins;
    public $themes;

    public function __construct(string $name, array $profile)
    {
        $this->setName($name);
        $this->setLang($profile['lang'] ?? '');
        $this->setPlugins($profile['plugins'] ?? '');
        $this->setThemes($profile['themes'] ?? '');
    }

    public function setName(string $name) : void
    {
        $this->name = strtolower($name);
    }

    public function setLang(string $lang) : void
    {
        $this->lang = Lang::resolveLang($lang);
    }

    public function setPlugins(string $plugins) : void
    {
        $this->plugins = $this->adaptRows($plugins);
    }

    public function setThemes(string $themes) : void
    {
        $this->themes = $this->adaptRows($themes);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getLang() : string
    {
        return $this->lang;
    }

    public function getPlugins() : array
    {
        $plugins = [];
        foreach ($this->plugins as $plugin) {
            $plugins[$plugin] = "https://downloads.wordpress.org/plugin/$plugin.zip";
        }
        return $plugins;
    }

    public function getThemes() : array
    {
        $themes = [];
        foreach ($this->themes as $theme) {
            $themes[$theme] = "https://downloads.wordpress.org/theme/$theme.zip";
        }
        return $themes;
    }

    public static function createEmpty() : self
    {
        return new self('default', ['lang' => '', 'plugins' => '']);
    }

    protected function adaptRows(string $rows) : array
    {
        return array_map(function($value) {
            return strtolower(trim($value));
        }, explode(',', $rows));
    }
}