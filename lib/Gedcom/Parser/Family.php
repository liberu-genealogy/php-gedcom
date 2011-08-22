<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Family extends \Gedcom\Parser\Component
{
    protected static $_eventTypes = array('ANUL','CENS','DIV','DIVF','ENGA','MARR',
        'MARB','MARC','MARL','MARS');
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $family = new \Gedcom\Record\Family();
        $family->refId = $identifier;
        
        $parser->getGedcom()->addFamily($family);
        
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
                case 'HUSB':
                    $family->husbandId = $parser->normalizeIdentifier($record[2]);
                break;    
                
                case 'WIFE':
                    $family->wifeId = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'CHIL':
                    $family->children[] = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'NCHI':
                    $family->nchi = trim($record[2]);
                break;
                
                case 'SUBM':
                    $family->addSubmitter($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'RIN':
                    $family->rin = trim($record[2]);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $family->chan = $chan;
                break;
                
                case 'SLGS':
                    $slgs = \Gedcom\Parser\Family\SealingSpouse::parse($parser);
                    $family->addSealingSpouse($slgs);
                break;
                
                case 'REFN':
                    $ref = \Gedcom\Parser\Refn::parse($parser);
                    $family->addRefn($ref);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $family->addNoteRef($note);
                    else
                        $family->addNote($note);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $family->addSourceCitationRef($citation);
                    else
                        $family->addSourceCitation($citation);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjeRef::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Obje\Ref'))
                        $family->addObjeRef($object);
                    else
                        $family->addObje($object);
                break;
                
                default:
                    if($recordType == 'EVEN' || in_array($recordType, self::$_eventTypes))
                    {
                        $event = \Gedcom\Parser\Family\Event::parse($parser);
                        $family->addEvent($event);
                    }
                    else
                    {
                        $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                    }
                break;
            }
            
            $parser->forward();
        }
        
        return $family;
    }
}
