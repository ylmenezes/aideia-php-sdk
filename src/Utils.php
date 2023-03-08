<?php

namespace Aideia;

class Utils
{
    public static function dd($param)
    {
        var_dump("<pre>", $param);
        die;
    }

    public static function getRoute($route, $params = null)
    {

        if ($params !== null && is_array($params)) {
            $placeholders = '/\:(\w+)/im';
            preg_match_all($placeholders, $route, $matches);
            $variables = $matches[1];

            foreach ($variables as $value) {
                if (isset($params[$value])) {
                    $route = str_replace(':' . $value, $params[$value], $route);
                    unset($params[$value]);
                }
            }
        }

        return $route;
    }
}
