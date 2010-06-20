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
            
            // We only process 0 level records here. Sub levels are processed
            // in methods for those data types (individuals, sources, etc)
            
            if($record[0] == '0')
            {
                $recordType = trim($record[1], '@');
                
                if(substr($recordType, 0, 1) == 'S')
                {
                    $identifier = substr($recordType, 1);
                    
                    $source = $this->_gedcom->createSource($identifier);
                    
                    $this->parseSource($source);
                }
                else if(substr($recordType, 0, 1) == 'I')
                {
                    $identifier = substr($recordType, 1);
                    
                    $person = $this->_gedcom->createPerson($identifier);
                    
                    $this->parsePerson($person);
                }
                else if(substr($recordType, 0, 1) == 'F')
                {
                    $identifier = substr($recordType, 1);
                    
                    $family = $this->_gedcom->createFamily($identifier);
                    
                    $this->parseFamily($family);
                }
                else if(trim($recordType) == 'HEAD')
                {
                    // What should we do with this? Log it? 
                }
                else if(substr($recordType, 0, 2) == 'NI')
                {
                    // TODO
                    // [7] => 15619: (Unhandled) 0|@NI1241@|NOTE - #72
                }
                else if(substr($recordType, 0, 2) == 'NS')
                {
                    // TODO
                    // [1] => 110683: (Unhandled) 0|@NS26371@|NOTE ABBR Oak Grove CemeteryTEXT
                        // http://www.rootsweb.com/~mikent/cemeteries/paris/oakgrove/oakgrove.html - #78
                }
                else
                {
                    // FIXME - uncomment and implement record types
                    //$this->logUnhandledRecord('#' . __LINE__);
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
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord('P');
            
            if($record[0] == '0')
            {
                $this->_currentLine--;
                break;
            }
            else if($record[0] == '1')
            {
                $recordType = trim($record[1]);
                
                $handler = 'parse' . $recordType . 'Record';
                
                if(is_callable(array($this, $handler)))
                {
                    if(isset($record[2]))
                        $this->$handler($person, trim($record[2]));
                    else
                        $this->$handler($person);
                }
                else
                {
                    $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
    protected function parseFamily(&$family)
    {
        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if($record[0] == '0')
            {
                $this->_currentLine--;
                break;
            }
            else if($record[0] == '1')
            {
                $recordType = trim($record[1]);
                
                $familyId = trim(trim($record[1]), '@F');
                
                switch($recordType)
                {
                    case 'HUSB':
                        $family->husbandId = trim(trim($record[2]), '@I');
                    break;    
                    
                    case 'WIFE':
                        $family->wifeId = trim(trim($record[2]), '@I');
                    break;
                    
                    case 'CHIL':
                        $family->children[] = trim(trim($record[2]), '@I');
                    break;
                    
                    case 'MARR':
                        $this->parseEventRecord($family, 'marriage');
                    break;
                    
                    case 'DIV':
                        $this->parseEventRecord($family, 'divorce');
                    break;
                    
                    case 'NOTE':
                        $family->notes[] = trim(trim($record[2]), '@NF');
                    break;
                    
                    default:
                        $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
    protected function parseGenericInformation(&$person, $type, $data)
    {
        $this->_currentLine++;
        
        $attribute = $person->addAttribute($type, $data);
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= 1)
            {
                $this->_currentLine--;
                
                return;
            }
            else if((int)$record[0] == 2)
            {
                $recordType = trim($record[1]);
                
                switch($recordType)
                {
                    case 'SOUR':
                        $reference = $this->_gedcom->createReference($this->normalizeIdentifier($record[2], 'S'), $type);
                        
                        $this->parseReference($reference, $record[0]);
                        
                        $attribute->addReference($reference);
                    break;
                    
                    default:
                        $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            /*else if((int)$record[0] > (int)$atLevel)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
    protected function parseNameRecord(&$person, $value)
    {
        $this->parseGenericInformation($person, 'name', $value);
    }
    
    
    /**
     *
     */
    protected function parseSexRecord(&$person, $value)
    {
        $this->parseGenericInformation($person, 'sex', $value);
    }
    
    
    /**
     *
     */
    protected function parseFamcRecord(&$person)
    {
        $record = $this->getCurrentLineRecord();
        
        $refId = trim(trim($record[2]), '@F');
        
        $person->famc[$refId] = $refId;
        
        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= 1)
            {
                $this->_currentLine--;
                
                break;
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
     */
    protected function parseFamsRecord(&$person)
    {
        $record = $this->getCurrentLineRecord();
        
        $refId = trim(trim($record[2]), '@F');
        
        $person->fams[$refId] = $refId;
        
        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= 1)
            {
                $this->_currentLine--;
                
                break;
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
     */
    protected function parseBirtRecord(&$person)
    {
        $this->parseEventRecord($person, 'birth');
    }
    
    
    /**
     *
     */
    protected function parseChrRecord(&$person)
    {
        $this->parseEventRecord($person, 'christening');
    }
    
    
    /**
     *
     */
    protected function parseReference(&$reference, $atLevel)
    {
        $this->_currentLine++;
        
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
                        
                        $this->parseData($data, $record[0]);
                    break;
                    
                    default:
                        $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            /*else if((int)$record[0] > (int)$atLevel)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
    protected function parseData(&$data, $atLevel)
    {
        $this->_currentLine++;
        
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
            /*else if((int)$record[0] > (int)$atLevel)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
    protected function parseBapmRecord(&$person)
    {
        $this->parseEventRecord($person, 'baptism');
    }
    
    
    /**
     *
     */
    protected function parseDeatRecord(&$person)
    {
        $this->parseEventRecord($person, 'death', array('CAUS' => 'cause'));
    }
    
    
    /**
     *
     */
    protected function parseBuriRecord(&$person)
    {
        $this->parseEventRecord($person, 'burial');
    }
    
    
    /**
     *
     */
    protected function parseEducRecord(&$person)
    {
        $this->parseEventRecord($person, 'education');
    }
    
    
    /**
     *
     */
    protected function parseOccuRecord(&$person)
    {
        $this->parseEventRecord($person, 'occupation');
    }
    
    
    /**
     *
     *
     */
    protected function parseEventRecord(&$person, $eventType, $additionalAttr = array())
    {
        $this->_currentLine++;
        
        $event = $person->addEvent($eventType);
        
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
                    case 'TYPE':
                        $event->type = trim($record[2]);
                    break;
                    
                    case 'DATE':
                        $event->date = trim($record[2]);
                    break;
                    
                    case 'PLAC':
                        $event->place = trim($record[2]);
                    break;
                    
                    case 'SOUR':
                        $reference = $this->_gedcom->createReference($this->normalizeIdentifier($record[2], 'S'), $eventType);
                        
                        $this->parseReference($reference, $record[0]);
                        
                        $event->addReference($reference);
                    break;
                    
                    default:
                        if(isset($additionalAttr[$recordType]))
                            $event->$additionalAttr[$recordType] = trim($record[2]);
                        else
                            $this->logUnhandledRecord('#' . __LINE__);
                }
            }
            /*else if((int)$record[0] > 2)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
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
    protected function parseCensRecord(&$person)
    {
        $this->parseEventRecord($person, 'census');
    }
    
    
    /**
     *
     */
    protected function parseEvenRecord(&$person)
    {
        $this->parseEventRecord($person, 'unknown');
    }
    
    
    /**
     *
     */
    protected function parseResiRecord(&$person)
    {
        $this->parseEventRecord($person, 'residence');
    }
    
    
    /**
     *
     */
    protected function parseImmiRecord(&$person)
    {
        $this->parseEventRecord($person, 'immigration');
    }
    
    
    /**
     *
     */
    protected function parsePropRecord(&$person)
    {
        $this->parseEventRecord($person, 'property');
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

