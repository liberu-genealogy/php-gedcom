<?php

namespace Gedcom\Record\Individual;

require_once __DIR__ . '/../../Record.php';
require_once __DIR__ . '/../Reference.php';

/**
 *
 *
 */
class Attribute extends \Gedcom\Record\Event
{
    public $type = null;
    public $attribute = null;
}
