<?php

namespace Greip;

class Config
{
    public static $APIKey = '';

    /**
     * setKey method 
     *
     * @param string $key Pass you API Key as a string here. You can also store it in a .env file and pass a variable that returns the API Key as a string.
     *
     * @return bool
     */
    public function setKey($key): bool
    {
        if (!empty($key)) {
            self::$APIKey = $key;
            return true;
        } else {
            return false;
        }
    }

    public function getKey(): string
    {
        return self::$APIKey;
    }
}
