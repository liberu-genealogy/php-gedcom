<?php

namespace Gedcom\Record\Note;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Text extends \Gedcom\Record
{
    public $note = null;
    public $sources = array();
}
