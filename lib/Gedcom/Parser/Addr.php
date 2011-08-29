<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Addr extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        $line = trim($record[2]);
        
        $addr = new \Gedcom\Record\Addr();
        $addr->addr = $line;
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtolower(trim($record[1]));
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            if($addr->hasAttribute($recordType))
                $addr->$recordType = trim($record[2]);
            else if ($recordType == 'cont')
            {
                // FIXME: Can have CONT on multiple attributes
                $addr->addr .= "\n";
                if(isset($record[2]))
                    $addr->addr .= trim($record[2]);
            }
            else
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            
            $parser->forward();
        }
        
        return $addr;
    }
}
