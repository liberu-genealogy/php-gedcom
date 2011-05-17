<?php

namespace Gedcom\Record\Header;

require_once __DIR__ . '/../../Record.php';

class Source extends \Gedcom\Record
{
    public $source = null;
    public $version = null;
    public $name = null;
    public $corp = null;
    public $data = null;
}
