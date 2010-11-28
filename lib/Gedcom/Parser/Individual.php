<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/Base.php';
require_once __DIR__ . '/../Record/Person.php';

/**
 *
 *
 */
class Individual extends Base
{
    protected $_individual = null;
    
   
    /**
     *
     *
     */
    public function init()
    {
        $this->_individual = $this->_gedcom->createPerson(); //new \Gedcom\Record\Person();
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
        
        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord('P');
            
            if($record[0] == '0')
            {
                $this->_currentLine--;
                break;
            }
            else if((int)$record[0] == 1 && trim($record[1]) == 'CHAN')
            {
                $this->_currentLine++;
                
                $this->_individual->change = new \Gedcom\Record\Change();
                
                while($this->_currentLine < count($this->_file))
                {
                    $record = $this->getCurrentLineRecord();
                    
                    if((int)$record[0] <= 1)
                    {
                        $this->_currentLine--;
                        break;
                    }
                    else if((int)$record[0] == 2 && trim($record[1] == 'DATE'))
                    {
                        if(isset($record[2]))
                            $this->_individual->change->date = trim($record[2]);
                    }
                    else if((int)$record[0] == 3 && trim($record[1] == 'TIME'))
                    {
                        if(isset($record[2]))
                            $this->_individual->change->time = trim($record[2]);
                    }
                    else
                    {
                        $this->logUnhandledRecord(__LINE__);
                    }
                    
                    $this->_currentLine++;
                }
            }
            else if($record[0] == '1')
            {
                $recordType = trim($record[1]);
                
                $handler = 'parse' . $recordType . 'Record';
                
                if(is_callable(array($this, $handler)))
                {
                    if(isset($record[2]))
                        $this->$handler($this->_individual, trim($record[2]));
                    else
                        $this->$handler($this->_individual);
                }
                else
                {
                    $this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $this->logUnhandledRecord(__LINE__);
            }
            
            $this->_currentLine++;
        }

        return $this->_individual;
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
                        $this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > (int)$atLevel)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $this->logUnhandledRecord(__LINE__);
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
    protected function parseRinRecord(&$person, $value)
    {
        $this->parseGenericInformation($person, 'rin', $value);
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
                $this->logUnhandledRecord(__LINE__);
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
                $this->logUnhandledRecord(__LINE__);
            }
            
            $this->_currentLine++;
        }
    }
    
    
    /**
     *
     *
     */
    protected function parseObjeRecord(&$person)
    {
        $record = $this->getCurrentLineRecord();
       
        $this->_currentLine++;
       
        $parser = new \Gedcom\Parser\Object();
        
        $person->objects[] = $parser->parseFile($this->_file, $this->_currentLine, 1);

        $this->_errorLog += $parser->getErrors();
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
                        $this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > (int)$atLevel)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $this->logUnhandledRecord(__LINE__);
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
                        $data->text .= "\n" . (isset($record[2]) ? trim($record[2]) : '');
                    break;
                    
                    case 'CONC':
                        $data->text .= trim($record[2]);
                    break;
                    
                    default:
                        $this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > (int)$atLevel)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $this->logUnhandledRecord(__LINE__);
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
    protected function parseEventRecord(&$person, $eventType = null, $additionalAttr = array())
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
                        if(!empty($record[2]))
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
                            $this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > 2)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $this->logUnhandledRecord( __LINE__);
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
    protected function parseNoteRecord(&$person)
    {
        $record = $this->getCurrentLineRecord();
        
        if(isset($record[2]) && preg_match('/\@N([0-9]*)\@/i', $record[2]) > 0)
        {
            $person->addNote($this->normalizeIdentifier($record[2], 'N'));
        }
        else
        {
            echo '<pre>' . print_r(__FILE__ . "/" . __LINE__ . ": ", true) . '</pre>';
            echo '<pre>' . print_r($record, true) . '</pre>';
        }
    }

}

