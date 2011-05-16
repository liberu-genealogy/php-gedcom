<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Address extends \Gedcom\Record
{
    public $addr = null;
    public $adr1 = null;
    public $adr2 = null;
    public $city = null;
    public $stae = null;
    public $post = null;
    public $ctry = null;
    
}
