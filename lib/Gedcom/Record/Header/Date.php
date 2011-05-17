<?php

namespace Gedcom\Record\Header;

require_once __DIR__ . '/../../Record.php';

class Date extends \Gedcom\Record
{
    public $date = null;
    public $time = null;
}
