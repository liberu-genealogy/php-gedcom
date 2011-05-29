<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Repo extends \Gedcom\Parser\Component
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
        
        $repo = new \Gedcom\Record\Repo();
        $repo->refId = $identifier;
        
        $parser->getGedcom()->addRepo($repo);
        
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
                case 'NAME':
                    $repo->name = trim($record[2]);
                break;
                
                case 'ADDR':
                    $addr = \Gedcom\Parser\Address::parse($parser);
                    $repo->addr = &$addr;
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phone::parse($parser);
                    $repo->addPhon($phone);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteReference::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $repo->addNoteReference($note);
                    else
                        $repo->addNote($note);
                break;
                
                case 'REFN':
                    $referenceNumber = \Gedcom\Parser\ReferenceNumber::parse($parser);
                    $repo->addRefn($referenceNumber);
                break;
                
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $repo->chan = &$change;
                break;
                
                case 'RIN':
                    $repo->rin = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $repo;
    }
}
