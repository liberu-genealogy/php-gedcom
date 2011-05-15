<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/../Record/Person.php';

/**
 *
 *
 */
class Individual extends \Gedcom\Parser\Component
{
    protected static $_eventTypes = array('BIRT','CHR','BAPM','BLES','ADOP','GRAD','DEAT',
        'BURI','EDUC', 'OCCU','CENS','RESI','IMMI','PROP','BARM','BASM','RETI','WILL');
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth = (int)$record[0];
        
        $person = &$parser->getGedcom()->createPerson($identifier);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $person->change = &$change;
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\Object::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $person->addObjectReference($object);
                    else
                        $person->addObject($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $person->addNoteReference($note);
                    else
                        $person->addNote($note);
                break;
                
                default:
                    if($recordType == 'EVEN' || in_array($recordType, self::$_eventTypes))
                    {
                        $event = \Gedcom\Parser\Individual\Event::parse($parser);
                        $person->addEvent($event);
                    }
                    else
                    {
                        $handler = 'parse' . $recordType . 'Record';
                        
                        if(is_callable(array(get_class(), $handler)))
                        {
                            if(isset($record[2]))
                                call_user_func(array(get_class(), $handler), $parser, $person, trim($record[2]), 1);
                            else
                                call_user_func(array(get_class(), $handler), $parser, $person, 1);
                        }
                        else
                        {
                            // FIXME
                            $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                        }
                    }
            }
            
            $parser->forward();
        }
        
        return $person;
    }
    
    /**
     *
     */
    protected static function parseNameRecord(&$parser, &$person, $value)
    {
        self::parseGenericInformation($parser, $person, 'name', $value);
    }
    
    /**
     *
     */
    protected static function parseRinRecord(&$parser, &$person, $value)
    {
        self::parseGenericInformation($parser, $person, 'rin', $value);
    }
    
    /**
     *
     */
    protected static function parseSexRecord(&$parser, &$person, $value)
    {
        self::parseGenericInformation($parser, $person, 'sex', $value);
    }
    
    /**
     *
     */
    protected static function parseEvenRecord(&$parser, &$person)
    {
        //self::parseEventRecord($parser, $person, 'unknown');
    }
    
    /**
     *
     *
     */
    protected static function parseGenericInformation(&$parser, &$person, $type, $data)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $attribute = $person->addAttribute($type, $data);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int)$record[0];
            $recordType = strtoupper(trim($record[1]));
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'SOUR':
                    $reference = $parser->getGedcom()->createReference($parser->normalizeIdentifier($record[2]), $type);
                    
                    self::parseReference($parser, $reference, $record[0]);
                    
                    $attribute->addReference($reference);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $attribute->addNoteReference($note);
                    else
                        $attribute->addNote($note);
                break;
                
                default:
                    // FIXME
                    //$this->logUnhandledRecord(__LINE__);
            }
            
            $parser->forward();
        }
    }
    
    
    /**
     *
     *
     */
    protected static function parseReference(&$parser, &$reference)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int)$record[0];
            $recordType = strtoupper(trim($record[1]));
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'PAGE':
                    $reference->page = trim($record[2]);
                break;
                
                case 'DATA':
                    $data = $reference->addData();
                    // FIXME (missing method):
                    //self::parseData($parser, $data, $record[0]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $reference->addNoteReference($note);
                    else
                        $reference->addNote($note);
                break;
                
                default:
                    // FIXME
                    //$this->logUnhandledRecord(__LINE__);
            }
            
            $parser->forward();
        }
    }
}
