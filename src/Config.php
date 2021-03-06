<?php

namespace Mongoob;

class Config
{
    /**
     * @var array
     */
    private static $params = [];

    /**
     * Sets DB config params.
     *
     * @param array $arr
     */
    public static function setParam($arr)
    {
        foreach ($arr as $key => $value):
            self::$params[$key] = $value;
        endforeach;
    }

    /**
     * Unsets config param.
     *
     * @param string $key
     */
    public static function unsetParam($key)
    {
        if (!empty(self::$params[$key])):
            unset(self::$params[$key]);
        endif;
    }

    /**
     * Gets parameter value.
     *
     * @param string $key Key
     *
     * @return mixed|null
     */
    public static function getParam($key)
    {
        $returnValue = null;

        if (!empty(self::$params[$key])):
            $returnValue = self::$params[$key];
        endif;

        return $returnValue;
    }
}
