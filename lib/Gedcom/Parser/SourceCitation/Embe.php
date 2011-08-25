<?php
/**
 *
 */

namespace Gedcom\Parser\SourceCitation;

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
        
        $embedded = new \Gedcom\Record\SourceCitation\Embe();
        $embedded->source = $record[2];
        
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
                case 'CONT':
                    $embedded->source .= "\n";
                    
                    if(isset($record[2]))
                        $embedded->source .= trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $embedded->source .= ' ' . trim($record[2]);
                break;
            
                case 'TEXT':
                    $embedded->text = $parser->parseMultiLineRecord();
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
