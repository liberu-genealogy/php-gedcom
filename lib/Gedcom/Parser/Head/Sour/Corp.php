<?php
/**
 *
 */

namespace Gedcom\Parser\Head\Sour;

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
        
        $corp = new \Gedcom\Record\Head\Sour\Corp();
        $corp->corp = trim($record[2]);
        
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
                case 'ADDR':
                    $corp->addr = \Gedcom\Parser\Addr::parse($parser);
                break;
                
                case 'PHON':
                    $corp->addPhon(trim($record[2]));
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $corp;
    }
}
