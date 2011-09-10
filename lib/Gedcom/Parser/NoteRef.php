<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 */
class NoteRef extends \Gedcom\Parser\Component
{
    /**
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $note = new \Gedcom\Record\NoteRef();
        
        if(preg_match('/^@(.*)@$/', trim($record[2])))
            $note->setIsReference(true);
        else
            $note->setIsReference(false);
        
        $note->note = $record[2];
        
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
                case 'CONT':
                    $note->note .= "\n";
                    
                    if(isset($record[2]))
                        $note->note .= trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $note->note .= ' ' . trim($record[2]);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $note->addSour($sour);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $note;
    }
}
