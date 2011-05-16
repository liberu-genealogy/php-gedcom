<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/../Record/Individual.php';

/**
 *
 *
 */
class Individual extends \Gedcom\Parser\Component
{
    protected static $_eventTypes = array('BIRT','CHR','BAPM','BLES','ADOP','GRAD','DEAT',
        'BURI','EDUC', 'OCCU','CENS','RESI','IMMI','PROP','BARM','BASM','RETI','WILL');
    
    protected static $_attrTypes = array();
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth = (int)$record[0];
        
        $individual = &$parser->getGedcom()->createIndividual($identifier);
        
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
                case 'SEX':
                    $individual->sex = trim($record[2]);
                break;
                
                case 'RIN':
                    $individual->rin = trim($record[2]);
                break;
                
                case 'RESN':
                    $individual->resn = trim($record[2]);
                break;
                
                case 'RFN':
                    $individual->rfn = trim($record[2]);
                break;
                
                case 'AFN':
                    $individual->afn = trim($record[2]);
                break;
                
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $individual->change = &$change;
                break;
                
                case 'FAMS':
                    $fams = \Gedcom\Parser\Individual\Family\Spouse::parse($parser);
                    $individual->addSpouseFamily($fams);
                break;
                
                case 'FAMC':
                    $famc = \Gedcom\Parser\Individual\Family\Child::parse($parser);
                    $individual->addChildFamily($famc);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\Object::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $individual->addObjectReference($object);
                    else
                        $individual->addObject($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $individual->addNoteReference($note);
                    else
                        $individual->addNote($note);
                break;
                
                default:
                    if($recordType == 'EVEN' || in_array($recordType, self::$_eventTypes))
                    {
                        $event = \Gedcom\Parser\Individual\Event::parse($parser);
                        $individual->addEvent($event);
                    }
                    else
                    {
                        $handler = 'parse' . $recordType . 'Record';
                        
                        if(is_callable(array(get_class(), $handler)))
                        {
                            if(isset($record[2]))
                                call_user_func(array(get_class(), $handler), $parser, $individual, trim($record[2]), 1);
                            else
                                call_user_func(array(get_class(), $handler), $parser, $individual, 1);
                        }
                        else
                        {
                            $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                        }
                    }
            }
            
            $parser->forward();
        }
        
        return $individual;
    }
    
    /**
     *
     */
    protected static function parseNameRecord(&$parser, &$individual, $value)
    {
        self::parseGenericInformation($parser, $individual, 'name', $value);
    }
    
    /**
     *
     */
    protected static function parseEvenRecord(&$parser, &$individual)
    {
        //self::parseEventRecord($parser, $individual, 'unknown');
    }
    
    /**
     *
     *
     */
    protected static function parseGenericInformation(&$parser, &$individual, $type, $data)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $attribute = $individual->addAttribute($type, $data);
        
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
