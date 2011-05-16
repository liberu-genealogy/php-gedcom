<?php

namespace Gedcom\Record\Individual;

require_once __DIR__ . '/../Event.php';

/**
 *
 *
 */
class Attribute extends \Gedcom\Record\Event
{
    public $type = null;
    public $attribute = null;
}
