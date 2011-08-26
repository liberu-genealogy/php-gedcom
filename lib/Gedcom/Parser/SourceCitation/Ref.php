<?php
/**
 *
 */

namespace Gedcom\Parser\SourceCitation;

/**
 *
 *
 */
class Ref extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $ref = new \Gedcom\Record\SourceCitation\Ref();
        $ref->sourceId = $parser->normalizeIdentifier($record[1]);
        
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
                case 'QUAY':
                    $ref->quay = trim($record[2]);
                break;
                
                case 'PAGE':
                    $ref->page = trim($record[2]);
                break;
                
                case 'EVEN':
                    $even = \Gedcom\Parser\SourceCitation\Event::parse($parser);
                    $note->even = $even;
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjectRef::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Ref'))
                        $ref->addObjectRef($object);
                    else
                        $ref->addObject($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $ref->addNoteRef($note);
                    else
                        $ref->addNote($note);
                break;
                
                case 'DATA':
                    $ref->data = \Gedcom\Parser\Source\Data::parse($parser);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $ref;
    }
}
