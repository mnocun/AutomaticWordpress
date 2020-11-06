<?php

namespace AutomaticWordpress;

use Exception;

class Configuration
{
    protected $parameters;

    public function __construct(string $envFile)
    {
        if (!is_readable($envFile)) {
            throw new Exception('Unable to open the environment file', 10);
        }

        $this->parameters = $this->parseEnv(file_get_contents($envFile));
    }

    public function __get(string $name)
    {
        return $this->getParam($name);
    }

    public function getParam(string $name) : string
    {
        return $this->parameters[strtolower($name)] ?? '';
    }

    protected function parseEnv(string $content) : array
    {
        $response = [];
        $envLines = explode(PHP_EOL, $content);
        $envLines = array_map('trim', $envLines);

        foreach($envLines as $line) {
            if (empty($line)) {
                continue;
            }
            [$name, $value] = explode('=', $line);
            $response[strtolower(trim($name))] = trim($value);
        }

        return $response;
    }

    public function __debugInfo()
    {
        return null;
    }
    
}