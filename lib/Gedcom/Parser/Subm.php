<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Subm extends \Gedcom\Parser\Component
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
        
        $subm = new \Gedcom\Record\Subm();
        $subm->refId = $identifier;
        
        $parser->getGedcom()->addSubm($subm);
        
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
                    $subm->name = trim($record[2]);
                break;
                
                case 'ADDR':
                    $addr = \Gedcom\Parser\Addr::parse($parser);
                    $subm->addr = &$addr;
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $subm->addPhon($phone);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $subm->addNoteRef($note);
                    else
                        $subm->addNote($note);
                break;
                
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    
                    if(is_a($obje, '\Gedcom\Record\Obje\Ref'))
                        $subm->addObjeRef($obje);
                    else
                        $subm->addObje($obje);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $subm->chan = $chan;
                break;
                
                case 'RIN':
                    $subm->rin = trim($record[2]);
                break;
                
                case 'RFN':
                    $subm->rfn = trim($record[2]);
                break;
                
                case 'LANG':
                    $subm->addLanguage(trim($record[2]));
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $subm;
    }
}
