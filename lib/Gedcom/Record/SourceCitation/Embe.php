<?php

namespace Gedcom\Record\SourceCitation;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Embe extends \Gedcom\Record
{
    public $source = null;
    public $text = null;
}
