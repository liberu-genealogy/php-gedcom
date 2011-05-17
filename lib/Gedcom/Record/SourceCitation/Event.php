<?php

namespace Gedcom\Record\SourceCitation;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Event extends \Gedcom\Record
{
    public $event = null;
    public $role = null;
}
