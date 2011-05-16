<?php

namespace Gedcom\Record\Individual\Event;

require_once __DIR__ . '/../../../Record.php';

class Birth extends \Gedcom\Record\Event
{
    public $famc = null;
}
