<?php

namespace Gedcom;

require_once __DIR__ . '/Gedcom.php';


/**
 *
 *
 */
class Parser
{
    protected $_gedcom = null;
    
    protected $_file = null;
    protected $_currentLine = 0;
    
    protected $_currentRecord = null;
    
    protected $_recordStack = array();
    
    protected $_errorLog = array();
    
    /**
     *
     *
     */
    public function parseFile($fileName)
    {
        $this->_file = file($fileName);
        
        $this->_gedcom = new Gedcom();
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if($record[0] == '0')
            {
                $recordType = trim($record[1], '@');
                
                if(substr($recordType, 0, 1) == 'S')
                {
                    $identifier = substr($recordType, 1);
                    
                    $source = $this->_gedcom->createSource($identifier);
                    
                    $this->parseSource($source);
                }
                if(substr($recordType, 0, 1) == 'I')
                {
                    $identifier = substr($recordType, 1);
                    
                    $person = $this->_gedcom->createPerson($identifier);
                    
                    $this->parsePerson($person);
                }
                else
                {
                    // FIXME - uncomment and implement record types
                    //echo 'Unhandled Row Type: <br/>';
                    //echo '<pre>' . print_r($record, true) . '</pre>';
                }
            }
            
            $this->_currentLine++;
        }
        
        return $this->_gedcom;
    }
    
    
    /**
     *
     *
     */
    protected function parseSource(&$source)
    {
        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord('S');
            
            if($record[0] == '0')
            {
                $this->_currentLine--;
                
                return;
            }
            else if($record[0] == '1' && trim($record[1]) == 'TITL')
            {
                $source->title = trim($record[2]);
            }
            else if($record[0] == '1' && trim($record[1]) == 'AUTH')
            {
                $source->author = trim($record[2]);
            }
            else if($record[0] == '1' && trim($record[1]) == 'PUBL')
            {
                $source->published = trim($record[2]);
            }
            else if($record[0] == '1' && trim($record[1]) == 'NOTE')
            {
                $source->addNote($this->normalizeIdentifier($record[2], 'NS'));
            }
            else if($record[0] == '1' && trim($record[1]) == 'REPO')
            {
                // FIXME: Not enough data to implement this right now. All
                // instances in my GEDCOM are blank
            }
            else if((int)$record[0] > 1)
            {
                // FIXME - Comment this out and implement all unhandled records
                
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }
            else
            {
                $this->logUnhandledRecord('#' . __LINE__);
            }
            
            $this->_currentLine++;
        }
    }
    
    
    /**
     *
     *
     */
    protected function parsePerson(&$person)
    {
        $this->_currentLine++;
        
        array_push($this->_recordStack, $person);
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord('P');
            
            if($record[0] == '0')
            {
                $this->_currentLine--;
                
                return;
            }
            else if($record[0] == '1')
            {
                $recordType = trim($record[1]);
                
                if(is_callable(array($this, 'parse' . $recordType . 'Record')))
                {
                    if(isset($record[2]))
                        $this->{'parse' . $recordType . 'Record'}(trim($record[2]));
                    else
                        $this->{'parse' . $recordType . 'Record'}();
                }
                else
                {
                    $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            else if((int)$record[0] > 1)
            {
                // FIXME - Comment this statement out once all known data
                // attributes are coded so that we fall through to error
                // logging below
                
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }
            else
            {
                $this->logUnhandledRecord('#' . __LINE__);
            }
            
            $this->_currentLine++;
        }
        
        array_pop($this->_recordStack);
    }
    
    
    /**
     *
     */
    protected function storeGenericInformation($type, $data)
    {
        $record = &$this->_recordStack[count($this->_recordStack) - 1];
        
        $record->$type = trim($data);
    }
    
    
    /**
     *
     */
    protected function parseNameRecord($value)
    {
        $this->storeGenericInformation('name', $value);
    }
    
    
    /**
     *
     */
    protected function parseSexRecord($value)
    {
        $this->storeGenericInformation('sex', $value);
    }
    
    
    /**
     *
     */
    protected function parseFamcRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseFamsRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseBirtRecord()
    {
        $this->parseEventRecord('birth');
    }
    
    
    /**
     *
     */
    protected function parseReference($atLevel)
    {
        $this->_currentLine++;
        
        $reference = &$this->_recordStack[count($this->_recordStack) - 1];
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= (int)$atLevel)
            {
                $this->_currentLine--;
                
                return;
            }
            else if((int)$record[0] == ((int)$atLevel) + 1)
            {
                $recordType = trim($record[1]);
                
                switch($recordType)
                {
                    case 'PAGE':
                        $reference->page = trim($record[2]);
                    break;
                
                    case 'DATA':
                        $data = $reference->addData();
                        
                        array_push($this->_recordStack, $data);
                        
                        $this->parseData($record[0]);
                        
                        array_pop($this->_recordStack);
                    break;
                    
                    default:
                        $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            //else if((int)$record[0] > (int)$atLevel)
            //{
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            //}
            else
            {
                $this->logUnhandledRecord('#' . __LINE__);
            }
            
            $this->_currentLine++;
        }
    }
    
    
    /**
     *
     */
    protected function parseData($atLevel)
    {
        $this->_currentLine++;
        
        $data = &$this->_recordStack[count($this->_recordStack) - 1];
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= (int)$atLevel)
            {
                $this->_currentLine--;
                
                return;
            }
            else if((int)$record[0] > ((int)$atLevel))
            {
                $recordType = trim($record[1]);
                
                switch($recordType)
                {
                    case 'TEXT':
                        $data->text = trim($record[2]);
                    break;
                    
                    case 'CONT':
                        $data->text .= "\n" . trim($record[2]);
                    break;
                    
                    case 'CONC':
                        $data->text .= trim($record[2]);
                    break;
                    
                    default:
                        $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            //else if((int)$record[0] > (int)$atLevel)
            //{
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            //}
            else
            {
                $this->logUnhandledRecord('#' . __LINE__);
            }
            
            $this->_currentLine++;
        }
    }
    
    
    /**
     *
     */
    protected function parseBapmRecord()
    {
        $this->parseEventRecord('baptism');
    }
    
    
    /**
     *
     */
    protected function parseDeatRecord()
    {
        $this->parseEventRecord('death', array('CAUS' => 'cause'));
    }
    
    
    /**
     *
     */
    protected function parseBuriRecord()
    {
        $this->parseEventRecord('burial');
    }
    
    
    /**
     *
     */
    protected function parseReliRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseEducRecord()
    {
        $this->parseEventRecord('education');
    }
    
    
    /**
     *
     */
    protected function parseOccuRecord()
    {
        $this->parseEventRecord('occupation');
    }
    
    
    /**
     *
     *
     */
    protected function parseEventRecord($eventType, $additionalAttr = array())
    {
        $this->_currentLine++;
        
        $person = &$this->_recordStack[count($this->_recordStack) - 1];
        
        $event = $person->addEvent($eventType);
        
        array_push($this->_recordStack, $event);
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] < 2)
            {
                $this->_currentLine--;
                
                break;
            }
            else if($record[0] == '2')
            {
                $recordType = trim($record[1]);
                
                switch($recordType)
                {
                    case 'DATE':
                        $event->date = trim($record[2]);
                    break;
                    
                    case 'PLAC':
                        $event->place = trim($record[2]);
                    break;
                    
                    case 'SOUR':
                        $reference = $this->_gedcom->createReference($this->normalizeIdentifier($record[2], 'S'), $eventType);
                        
                        array_push($this->_recordStack, $reference);
                        
                        $this->parseReference($record[0]);
                        
                        array_pop($this->_recordStack);
                        
                        $person->addSource($reference);
                    break;
                    
                    default:
                        if(isset($additionalAttr[$recordType]))
                            $event->$additionalAttr[$recordType] = trim($record[2]);
                        else
                            $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            //else if((int)$record[0] > 2)
            //{
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            //}
            else
            {
                $this->logUnhandledRecord('#' . __LINE__);
            }
            
            $this->_currentLine++;
        }
        
        array_pop($this->_recordStack);
    }
    
    
    
    /**
     *
     */
    protected function parseAddrRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseCensRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseEvenRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseResiRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseImmiRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parsePropRecord()
    {
        // TODO
    }
    
    
    /**
     *
     */
    protected function parseNoteRecord()
    {
        // TODO
    }
    
    
    /**
     *
     *
     */
    protected function getCurrentLineRecord()
    {
        $line = $this->_file[$this->_currentLine];
        
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
    protected function isPrimaryRecord($record, $identifier)
    {
        return $record[0] == '1' && strtolower(trim($record[1])) == strtolower($identifier);
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
            throw new Exception('Tag Mismatch: ' . $tag . ' - ' . $identifier);
        
        $identifier = substr($identifier, strlen($tag));
        
        return $identifier;
    }
    
}

