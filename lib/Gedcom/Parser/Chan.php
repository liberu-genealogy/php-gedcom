<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Chan extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $parser->forward();
        
        $chan = new \Gedcom\Record\Chan();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = trim($record[1]);
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'DATE':
                    $chan->date = trim($record[2]);
                break;
                
                case 'TIME':
                    $chan->time = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $chan->addNoteRef($note);
                    else
                        $chan->addNote($note);
                break;
            
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $chan;
    }
}
