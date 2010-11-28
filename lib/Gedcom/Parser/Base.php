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

    
    /**
     *
     *
     */
    protected function getCurrentLineRecord()
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
    protected function logUnhandledRecord($additionalInfo = '')
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

