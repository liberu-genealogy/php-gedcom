<?php

namespace Gedcom\Record\SourceCitation;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Reference extends \Gedcom\Record
{
    public $sourceId;
    public $page = null;
    public $event = null;
    public $data = null;
    public $quay = null;
    
}
