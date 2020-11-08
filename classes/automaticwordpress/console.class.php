<?php

namespace AutomaticWordpress;

use Exception;

class Console
{
    protected $isConsole;
    protected $scriptName;
    protected $location;
    protected $flags = [];

    public function __construct(?array $arguments)
    {
        $this->isConsole = !is_null($arguments) && is_array($arguments);
        if ($this->isConsole) {
            $this->processArguments($arguments);
        }
    }

    public static function echo(string $message) : void
    {
        echo $message.PHP_EOL;
        flush();
    }

    public function hasFlag(string $name) : bool
    {
        $name = strtolower($name);
        return isset($this->flags[$name]);
    }

    public function getFlag(string $name) : string
    {
        $name = strtolower($name);
        return trim( $this->flags[$name] ?? '' );
    }

    public function getLocation() : string
    {
        return trim($this->location, '\'" ');
    }

    public function getScriptName() : string
    {
        return $this->scriptName;
    }

    public function isConsole() : bool
    {
        return $this->isConsole;
    }

    protected function processArguments(array $arguments)
    {
        if (!isset($arguments[1])) {
            throw new Exception('The script requires a location', 22);
        }
        [$this->scriptName, $this->location] = $arguments;
        unset($arguments[0], $arguments[1]);

        if ((count($arguments) % 2)) {
            throw new Exception('Incorrect use of flags', 23);
        }

        foreach(array_chunk($arguments, 2) as [$flag, $name]) {
            if ($flag[0] !== '-' || $flag[1] !== '-') {
                throw new Exception("Incorrect flag name \"$flag\"", 23);
            }
            $this->flags[substr($flag, 2)] = trim($name, '\'" ');
        }
    }
}