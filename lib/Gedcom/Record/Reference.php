<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Reference extends \Gedcom\Record
{
    public $sourceId = null;
    public $attribute = null;
    
    public $data = array();
    

    public function &addData()
    {
        $this->data[] = new Data();
        
        return $this->data[count($this->data) - 1];
    }
    
}
