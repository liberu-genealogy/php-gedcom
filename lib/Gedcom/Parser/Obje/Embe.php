<?php
/**
 *
 */

namespace Gedcom\Parser\Obje;

/**
 *
 *
 */
class Embe extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $embedded = new \Gedcom\Record\Obje\Embe();
        
        $parser->forward();
        
        while(!$parser->eof())
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
                case 'TITL':
                    $embedded->title = trim($record[2]);
                break;
                
                case 'FILE':
                    $embedded->file = trim($record[2]);
                break;
                
                case 'FORM':
                    $embedded->form = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $embedded->addNoteRef($note);
                    else
                        $embedded->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $embedded;
    }
}
