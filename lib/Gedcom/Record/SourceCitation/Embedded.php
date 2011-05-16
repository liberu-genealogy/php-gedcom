<?php

namespace Gedcom\Record\SourceCitation;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Embedded extends \Gedcom\Record
{
    public $source = null;
    public $text = null;
}
