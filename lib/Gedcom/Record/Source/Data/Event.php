<?php

namespace Gedcom\Record\Source\Data;

require_once realpath(__DIR__ . '/../../../Record.php');

/**
 *
 */
class Event extends \Gedcom\Record
{
    public $date = null;
    public $place = null;
}
