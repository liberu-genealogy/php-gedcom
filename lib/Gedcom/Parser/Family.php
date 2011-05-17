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
        
        $family = $parser->getGedcom()->createFamily($identifier);
        
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
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $family->change = $change;
                break;
                
                case 'SLGS':
                    $slgs = \Gedcom\Parser\Family\SealingSpouse::parse($parser);
                    $family->addSealingSpouse($slgs);
                break;
                
                case 'REFN':
                    $ref = \Gedcom\Parser\ReferenceNumber::parse($parser);
                    $family->addReferenceNumber($ref);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $family->addNoteReference($note);
                    else
                        $family->addNote($note);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Reference'))
                        $family->addSourceCitationReference($citation);
                    else
                        $family->addSourceCitation($citation);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\Object::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $family->addObjectReference($object);
                    else
                        $family->addObject($object);
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
