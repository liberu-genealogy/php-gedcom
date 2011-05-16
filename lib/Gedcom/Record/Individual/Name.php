<?php

namespace Gedcom\Record\Individual;

require_once __DIR__ . '/../../Record.php';

/**
 *
 *
 */
class Name extends \Gedcom\Record
{
    public $name = null;
    public $npfx = null;
    public $givn = null;
    public $nick = null;
    public $spfx = null;
    public $surn = null;
    public $nsfx = null;
}
