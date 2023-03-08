<?php

namespace Aideia;

use Exception;

class AideiaException extends Exception
{

    public function __construct($exception, $code)
    {
        $this->code = $code;
        parent::__construct($exception, $this->code);
    }

    public function __toString()
    {
        return 'Erro: ' . $this->code . ' ' . $this->message . "\n";
    }

    public function __toArray()
    {
        return [
            'error' => true,
            'code' => $this->code,
            'message'  => $this->message,
        ];
    }
}