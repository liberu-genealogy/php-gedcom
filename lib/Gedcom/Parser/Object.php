<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/Base.php';


/**
 *
 *
 */
class Object extends Base
{
    protected $_object = null;
    
    public function __construct()
    {
        $this->_object = new \Gedcom\Record\Object();
    }
   
    
    /**
     *
     *
     */
    public function parseFile(&$fileLines, &$currentLine, $indent)
    {
        $this->_file = &$fileLines;
        $this->_currentLine = &$currentLine;
        $this->_indentationLevel = $indent;

        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
           
            if((int)$record[0] <= $this->_indentationLevel)
            {
                $this->_currentLine--;
                break;
            }
            else if(isset($record[1]) && $record[1] == 'FORM')
            {
                $this->_object->form = trim($record[2]);
            }
            else if(isset($record[1]) && $record[1] == 'TITL')
            {
                $this->_object->title = trim($record[2]);
            }
            else if(isset($record[1]) && $record[1] == 'FILE')
            {
                $this->_object->file = trim($record[2]);
            }
            else
            {
                $this->logUnhandledRecord(basename(__FILE__) . '/' . __LINE__);
            }

            $this->_currentLine++;
        }

        return $this->_object;
    }
}

