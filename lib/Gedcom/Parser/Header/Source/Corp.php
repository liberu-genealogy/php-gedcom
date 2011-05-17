<?php

namespace Gedcom\Parser\Header\Source;

/**
 *
 *
 */
class Corp extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $corp = new \Gedcom\Record\Header\Source\Corp();
        $corp->corp = trim($record[2]);
        
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
                case 'ADDR':
                    $corp->address = \Gedcom\Parser\Address::parse($parser);
                break;
                
                case 'PHON':
                    $corp->addPhone(trim($record[2]));
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $corp;
    }
}
