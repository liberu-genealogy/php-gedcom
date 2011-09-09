<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Obje extends \Gedcom\Parser\Component
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
        
        $obje = new \Gedcom\Record\Obje();
        $obje->id = $identifier;
        
        $parser->getGedcom()->addObje($obje);
        
        $parser->forward();
        
        while(!$parser->eof())
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
                    $obje->form = trim($record[2]);
                break;
                
                case 'TITL':
                    $obje->titl = trim($record[2]);
                break;
                
                case 'OBJE':
                    $obje->form = $this->normalizeIdentifier($record[2]);
                break;
                
                case 'RIN':
                    $obje->rin = trim($record[2]);
                break;
                
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $obje->addRefn($refn);
                break;
                
                case 'BLOB':
                    $obje->blob = $parser->parseMultiLineRecord();
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $obje->addNoteRef($note);
                    else
                        $obje->addNote($note);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $obje->chan = $chan;
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $obje;
    }
}
