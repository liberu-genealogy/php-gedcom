<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Source extends \Gedcom\Record
{
    public $title = null;
    public $author = null;
    public $published = null;
    
    public $notes = array();
    
    
    public function addNote($noteId)
    {
        $this->notes[$noteId] = $noteId;
    }
    
}
