<?php

namespace Gedcom\Parser\Header\Source;

require_once __DIR__ . '/../../../Record/Header/Source/Data.php';

/**
 *
 *
 */
class Data extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $data = new \Gedcom\Record\Header\Source\Data();
        $data->data = trim($record[2]);
        
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
                case 'DATE':
                    $data->date = trim($record[2]);
                break;
                
                case 'COPR':
                    $data->copr = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $corp;
    }
}
