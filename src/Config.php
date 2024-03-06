<?php

namespace Greip\API;

class Config
{
    public static $APIKey = null;

    /**
     * Set the API Key
     *
     * @param string $key The API Key
     *
     * @return bool
     */
    public function setToken($key): bool
    {
        if (!empty($key)) {
            self::$APIKey = $key;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the API Key
     *
     * @param string $key The API Key
     *
     * @return bool
     */
    public function setKey($key): bool
    {
        return self::setToken($key);
    }

    /**
     * Get the API Key
     *
     * @return string
     */
    public function getToken(): string
    {
        return self::$APIKey;
    }
}
