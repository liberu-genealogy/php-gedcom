<?php

namespace Gedcom;

abstract class Record
{
    public function __construct()
    {
        $memory = memory_get_usage(true);
        file_put_contents('memory.log', "Constructing [" . get_class($this) . "]: {$memory}\n", FILE_APPEND);
    }

    public $refId = null;
}
