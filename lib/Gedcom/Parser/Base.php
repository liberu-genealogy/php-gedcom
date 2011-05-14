<?php

namespace Gedcom\Parser;


/**
 *
 *
 */
abstract class Base
{
    protected $_file = null;
    protected $_currentLine = 0;

    protected $_gedcom;

    protected $_errorLog = array();
    
    
    /**
     *
     *
     */
    public function __construct(&$gedcom)
    {
        $this->_gedcom = &$gedcom;

        $this->init();
    }
   

    /**
     *
     *
     */
    public function init()
    {

    }
    
    public function forward()
    {
        $this->_currentLine++;
    }
    
    public function back()
    {
        $this->_currentLine--;
    }
    
    public function getCurrentLine()
    {
        return $this->_currentLine;
    }
    
    public function getFileLength()
    {
        return count($this->_file);
    }
    
    public function &getGedcom()
    {
        return $this->_gedcom;
    }
    
    
    /**
     *
     */
    public function parseMultiLineRecord()
    {
        $linesAdvanced = 0;
        
        $record = $this->getCurrentLineRecord();
        
        $depth = $record[0];
        
        $data = trim($record[2]);
        
        $this->forward();
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= (int)$depth)
            {
                if($linesAdvanced > 1)
                    $this->back();
                break;
            }
            
            switch($record[1])
            {
                case 'CONT':
                    if(isset($record[2]))
                        $data .= "\n" . trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $data .= ' ' . trim($record[2]);
                break;
                
                default:
                    $this->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                break;
            }
            
            $this->forward();
            $linesAdvanced++;
        }
        
        return $data;
    }
    
    /**
     *
     *
     */
    public function getCurrentLineRecord()
    {
        $line = trim($this->_file[$this->_currentLine]);
        
        return explode(' ', $line, 3);
    }
    
    
    /**
     *
     *
     */
    protected function logError($error)
    {
        $this->_errorLog[] = $error;
    }
    
    
    /**
     *
     *
     */
    public function logUnhandledRecord($additionalInfo = '')
    {
        $this->logError($this->_currentLine . ': (Unhandled) ' . trim(implode('|', $this->getCurrentLineRecord())) .
            (!empty($additionalInfo) ? ' - ' . $additionalInfo : ''));
    }
    
    
    /**
     *
     *
     */
    public function getErrors()
    {
        return $this->_errorLog;
    }
    
    
    /**
     *
     *
     */
    public function normalizeIdentifier($identifier, $tag)
    {
        $identifier = trim($identifier);
        $identifier = trim($identifier, '@');
        
        if(substr($identifier, 0, strlen($tag)) !== $tag)
            throw new \Exception('Tag Mismatch: [' . $tag . ':' . $identifier . '] on ' . $this->_currentLine);
        
        $identifier = substr($identifier, strlen($tag));
        
        return $identifier;
    }
}
