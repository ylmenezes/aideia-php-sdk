<?php 

namespace Aideia;

class Config
{
    private static $fileEndpoints = __DIR__ . '/config.json';

    public static function get($property)
    {
        $file = file_get_contents(self::$fileEndpoints);
        $content = json_decode($file, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erro ao carregar endpoints do arquivo");
        }
        
        if (isset($content[$property])) {
            return $content[$property];
        }

        return;
    }
}