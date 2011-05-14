<?php

namespace Gedcom\Parser;

//require_once __DIR__ . '/Base.php';
//require_once __DIR__ . '/../Record/Source.php';

/**
 *
 *
 */
class SourceCallNumber
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = str_replace('@', '', $record[2]);
        
        $depth = (int)$record[0];
        
        $caln = $parser->getGedcom()->createSourceCallNumber($identifier);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $lineDepth = (int)$record[0];
            
            if($lineDepth <= $depth)
            {
                $parser->back();
                break;
            }
            else if($lineDepth == $depth + 1 && trim($record[1]) == 'MEDI')
            {
                // FIXME
            }
            else
            {
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $caln;
    }
}
