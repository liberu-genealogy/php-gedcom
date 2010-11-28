<?php

namespace Gedcom;

abstract class Record
{
    public $refId = null;
    
    public $notes = array();
    public $note_references = array();
}

