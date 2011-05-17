<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Submitter extends \Gedcom\Record
{
    public $name = null;
    public $addr = null;
    public $rin = null;
    public $rfn = null;
    
    public $langs = array();
    public $phones = array();
    
    /**
     *
     */
    public function addLanguage($lang)
    {
        $this->langs[] = $lang;
    }
    
    /**
     *
     */
    public function addPhone(\Gedcom\Record\Phone &$phone)
    {
        $this->phones[] = $phone;
    }
}
