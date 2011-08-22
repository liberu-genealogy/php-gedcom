<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Object extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $object = new \Gedcom\Record\Object();
        $object->refId = $identifier;
        
        $parser->getGedcom()->addObject($object);
        
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
                case 'FORM':
                    $object->form = trim($record[2]);
                break;
                
                case 'TITL':
                    $object->titl = trim($record[2]);
                break;
                
                case 'OBJE':
                    $object->form = $this->normalizeIdentifier($record[2]);
                break;
                
                case 'RIN':
                    $object->rin = trim($record[2]);
                break;
                
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $object->addRefn($refn);
                break;
                
                case 'BLOB':
                    $object->blob = $parser->parseMultiLineRecord();
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $object->addNoteRef($note);
                    else
                        $object->addNote($note);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $object->chan = &$chan;
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $object;
    }
}
