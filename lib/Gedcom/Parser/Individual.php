<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/../Record/Individual.php';

/**
 *
 *
 */
class Individual extends \Gedcom\Parser\Component
{
    protected static $_eventTypes = array('ADOP','BIRT','BAPM','BARM','BASM','BLES','BURI',
        'CENS','CHR','CHRA','CONF','CREM','DEAT','EMIG','FCOM','GRAD','IMMI','NATU','ORDN',
        'RETI','PROB','WILL','EVEN');
    
    protected static $_attrTypes = array('CAST','EDUC','NATI','OCCU','PROP','RELI','RESI',
        'TITL','SSN','IDNO','DSCR','NCHI','NMR');
    
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
                case 'NAME':
                    $name = \Gedcom\Parser\Individual\Name::parse($parser);
                    $individual->addName($name);
                break;
                
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
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Reference'))
                        $individual->addSourceCitationReference($citation);
                    else
                        $individual->addSourceCitation($citation);
                break;
                
                default:
                    if($recordType == 'EVEN' || in_array($recordType, self::$_eventTypes))
                    {
                        $event = \Gedcom\Parser\Individual\Event::parse($parser);
                        $individual->addEvent($event);
                    }
                    else if(in_array($recordType, self::$_attrTypes))
                    {
                        $attribute = \Gedcom\Parser\Individual\Attribute::parse($parser);
                        $individual->addAttribute($attribute);
                    }
                    else
                    {
                        $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                    }
                break;
            }
            
            $parser->forward();
        }
        
        return $individual;
    }
}
