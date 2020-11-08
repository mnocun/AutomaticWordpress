<?php

namespace AutomaticWordpress;

use Exception;

class Console
{
    // Type of text
    const TEXT_NORMAL = 0;
    const TEXT_BOLD = 1;
    const TEXT_DIM = 2;
    const TEXT_UNDERLINE = 4;
    const TEXT_BLINK = 5;
    const TEXT_REVERSE = 7;
    const TEXT_HIDDEN = 8;

    // Text color
    const COLOR_DEFAULT = 39;
    const COLOR_BLACK = 30;
    const COLOR_RED = 31;
    const COLOR_GREEN = 32;
    const COLOR_YELLOW = 33;
    const COLOR_BLUE = 34;
    const COLOR_MAGENTA = 35;
    const COLOR_CYAN = 36;
    const COLOR_LIGHT_GRAY = 37;
    const COLOR_DARK_GRAY = 90;
    const COLOR_LIGHT_RED = 91;
    const COLOR_LIGHT_GREEN = 92;
    const COLOR_LIGHT_YELLOW = 93;
    const COLOR_LIGHT_BLUE = 94;
    const COLOR_LIGHT_MAGENTA = 95;
    const COLOR_LIGHT_CYAN = 96;
    const COLOR_WHITE = 97;

    // Background text
    const BG_DEFAULT = 49;
    const BG_BLACK = 40;
    const BG_RED = 41;
    const BG_GREEN = 42;
    const BG_YELLOW = 43;
    const BG_BLUE = 44;
    const BG_MAGENTA = 45;
    const BG_CYAN = 46;
    const BG_LIGHT_GRAY = 47;
    const BG_DARK_GRAY = 100;
    const BG_LIGHT_RED = 101;
    const BG_LIGHT_GREEN = 102;
    const BG_LIGHT_YELLOW = 103;
    const BG_LIGHT_BLUE = 104;
    const BG_LIGHT_MAGENTA = 105;
    const BG_LIGHT_CYAN = 106;
    const BG_WHITE = 107;

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

    public static function centerEcho(string $message, int $chnum = 50, $type = Console::TEXT_BLINK, bool $endLine = true) : void
    {
        self::echo(str_pad($message, $chnum, ' ', STR_PAD_BOTH), $type, $endLine);
    }

    public static function echo(string $message, $type = Console::TEXT_BLINK, bool $endLine = true) : void
    {
        $type = is_array($type) ? implode(';', $type) : $type;
        echo "\e[{$type}m";
        echo $message;
        if ($endLine) {
            echo PHP_EOL;
        }
        echo "\e[0;39;49m";
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