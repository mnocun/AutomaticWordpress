<?php

namespace AutomaticWordpress;

use Exception;

class Profiles
{
    protected $profiles;

    public function __construct(string $location = null)
    {
        if (!is_null($location) && is_readable($location)) {
            $this->profiles = parse_ini_file($location, true);
        }
    }

    public function getProfile(string $name) : ?Profile
    {
        if (!isset($this->profiles[$name])) {
            return null;
        }
        return new Profile($name, $this->profiles[$name]);
    }

}