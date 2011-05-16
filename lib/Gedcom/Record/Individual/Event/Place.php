<?php

namespace Gedcom\Record\Individual\Event;

require_once realpath(__DIR__ . '/../../../Record.php');

/**
 *
 */
class Place extends \Gedcom\Record
{
    public $place = null;
    public $form = null;
}
