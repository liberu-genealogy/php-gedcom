<?php

namespace Gedcom\Record\Individual\Event;

require_once __DIR__ . '/../../../Record.php';

class Adoption extends \Gedcom\Record\Event
{
    public $adop = null;
    public $famc = null;
}
