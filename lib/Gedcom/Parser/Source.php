<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Source
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = str_replace('@', '', $record[2]);
        
        $source = &$parser->getGedcom()->createSource($identifier);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord('S');
            
            if($record[0] == '0')
            {
                $parser->back();
                break;
            }
            else if($record[0] == '1' && trim($record[1]) == 'TITL')
            {
                $source->title = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'RIN')
            {
                $source->rin = trim($record[2]);
            }
            else if($record[0] == '1' && trim($record[1]) == 'AUTH')
            {
                $source->author = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'TEXT')
            {
                $source->text = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'PUBL')
            {
                $source->published = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'REPO')
            {
                $source->repository = \Gedcom\Parser\SourceRepositoryCitation::parse($parser);
            }
            else if($record[0] == '1' && trim($record[1]) == 'NOTE')
            {
                $note = \Gedcom\Parser\Note::parse($parser);
                
                if(is_a($note, '\Gedcom\Record\Note\Reference'))
                    $source->addNoteReference($note);
                else
                    $source->addNote($note);
            }
            else if($record[0] == '1' && trim($record[1]) == 'DATA')
            {
                $source->data = \Gedcom\Parser\Source\Data::parse($parser);
            }
            else if($record[0] == '1' && trim($record[1]) == 'OBJE')
            {
                $object = \Gedcom\Parser\Object::parse($parser);
                
                if(is_a($object, '\Gedcom\Record\Object\Reference'))
                    $source->addObjectReference($object);
                else
                    $source->addObject($object);
            }
            else if((int)$record[0] == 1 && trim($record[1]) == 'REFN')
            {
                $referenceNumber = \Gedcom\Parser\ReferenceNumber::parse($parser);
                $source->addReferenceNumber($referenceNumber);
            }
            else if((int)$record[0] == 1 && trim($record[1]) == 'CHAN')
            {
                $parser->forward();
                
                $source->change = new \Gedcom\Record\Change();
                
                while($parser->getCurrentLine() < $parser->getFileLength())
                {
                    $record = $parser->getCurrentLineRecord();
                    
                    if((int)$record[0] <= 1)
                    {
                        $parser->back();
                        break;
                    }
                    else if((int)$record[0] == 2 && trim($record[1] == 'DATE'))
                    {
                        if(isset($record[2]))
                            $source->date = trim($record[2]);
                    }
                    else if((int)$record[0] == 3 && trim($record[1] == 'TIME'))
                    {
                        if(isset($record[2]))
                            $source->time = trim($record[2]);
                    }
                    else
                    {
                        $parser->logUnhandledRecord(__LINE__);
                    }
                    
                    $parser->forward();
                }
            }
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $source;
    }
}
