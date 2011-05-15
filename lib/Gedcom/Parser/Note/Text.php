<?php

namespace Gedcom\Parser\Note;


/**
 *
 *
 */
class Text
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $depth = (int)$record[0];
        
        $text = new \Gedcom\Record\Note\Text();
        $text->note = $record[2];
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            
            if((int)$record[0] <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($record[1])
            {
                case 'CONT':
                    if(isset($record[2]))
                        $text->note .= "\n" . trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $text->note .= ' ' . trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $text;
    }
}
