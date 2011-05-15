<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Source extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth = (int)$record[0];
        
        $source = &$parser->getGedcom()->createSource($identifier);
        
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
                case 'TITL':
                    $source->title = $parser->parseMultilineRecord();
                break;
                
                case 'RIN':
                    $source->rin = trim($record[2]);
                break;
                
                case 'AUTH':
                    $source->author = $parser->parseMultilineRecord();
                break;
                
                case 'TEXT':
                    $source->text = $parser->parseMultilineRecord();
                break;
                
                case 'PUBL':
                    $source->published = $parser->parseMultilineRecord();
                break;
                
                case 'REPO':
                    $source->repository = \Gedcom\Parser\SourceRepositoryCitation::parse($parser);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $source->addNoteReference($note);
                    else
                        $source->addNote($note);
                break;
                
                case 'DATA':
                    $source->data = \Gedcom\Parser\Source\Data::parse($parser);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\Object::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $source->addObjectReference($object);
                    else
                        $source->addObject($object);
                break;
                
                case 'REFN':
                    $referenceNumber = \Gedcom\Parser\ReferenceNumber::parse($parser);
                    $source->addReferenceNumber($referenceNumber);
                break;
                
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $source->change = &$change;
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $source;
    }
}
