<?php

namespace Gedcom\Record\Source;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Data extends \Gedcom\Record
{
    public $events = array();
    public $agnc = null;
    public $date = null;
    
}
