<?php

namespace Gedcom\Record\Note;

require_once __DIR__ . '/../../Record.php';


/**
 *
 *
 */
class Reference extends \Gedcom\Record\Note
{
    public $noteId = null;
    public $sources = array();
}
