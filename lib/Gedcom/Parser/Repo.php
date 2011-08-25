<?php
/**
 *
 */

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
                    $addr = \Gedcom\Parser\Addr::parse($parser);
                    $repo->addr = $addr;
                break;
                
                case 'PHON':
                    $phon = \Gedcom\Parser\Phon::parse($parser);
                    $repo->addPhon($phon);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $repo->addNoteRef($note);
                    else
                        $repo->addNote($note);
                break;
                
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $repo->addRefn($refn);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $repo->chan = $chan;
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
