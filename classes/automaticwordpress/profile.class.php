<?php

namespace AutomaticWordpress;

use Exception;

class Profile
{
    public $name;
    public $lang;
    public $plugins;

    public function __construct(string $name, array $profile)
    {
        $this->setName($name);
        $this->setLang($profile['lang'] ?? '');
        $this->setPlugins($profile['plugins'] ?? '');
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
        $this->plugins = array_map('trim', explode(',', $plugins) );
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
        foreach($this->plugins as $plugin) {
            $plugins[$plugin] = "https://downloads.wordpress.org/plugin/$plugin.zip";
        }
        return $plugins;
    }

    public static function createEmpty() : self
    {
        return new self('default', ['lang' => '', 'plugins' => '']);
    }
}