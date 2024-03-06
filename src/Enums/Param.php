<?php
namespace Greip\API\Enums;

use ReflectionClass;

abstract class Param
{
    const LANGUAGE = "language";
    const FLAG = "flag";
    const CURRENCY = "currency";
    const TIMEZONE = "timezone";
    const LOCATION = "location";
    const SECURITY = "security";
    const DEVICE = "device";

    /**
     * Get all constants of this class
     *
     * @return array
     */
    public static function values()
    {
        $reflector = new ReflectionClass(__CLASS__);
        return $reflector->getConstants();
    }

    /**
     * Checks if a constant exist by value
     *
     * @param string $constant_value E.g: `isExist('dash')`.
     * @return boolean
     */
    public static function isExist($constant_value)
    {
        $constants = self::values();
        return in_array($constant_value, $constants, true);
    }
}
