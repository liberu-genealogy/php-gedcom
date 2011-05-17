<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Header extends \Gedcom\Record
{
    public $source = null;
    public $dest = null;
    public $date = null;
    public $subm = null;
    public $subn = null;
    public $file = null;
    public $copr = null;
    public $gedc = null;
    public $char = null;
    public $lang = null;
    public $plac = null;
    public $note = null;

}
