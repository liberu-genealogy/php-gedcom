<?php

namespace Gedcom\Record\Header;

require_once __DIR__ . '/../../Record.php';

class Gedcom extends \Gedcom\Record
{
    public $version = null;
    public $form = null;
}
