<?php

namespace Gedcom\Record\Object;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Embedded extends \Gedcom\Record
{
    public $form = null;
    public $title = null;
    public $file = null;
}
