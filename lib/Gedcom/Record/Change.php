<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

/**
 *
 *
 */
class Change extends \Gedcom\Record
{
    public $date = null;
    public $time = null;
}
