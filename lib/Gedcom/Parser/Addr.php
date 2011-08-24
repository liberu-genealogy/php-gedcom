<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/../Record/Addr.php';

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
        
        $addr = new \Gedcom\Record\Addr();
        
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
                    $addr->addr .= "\n";
                    if(isset($record[2]))
                        $addr->addr .= trim($record[2]);
                break;
                
                case 'ADR1':
                    $addr->adr1 = trim($record[2]);
                break;
                
                case 'ADR2':
                    $addr->adr2 = trim($record[2]);
                break;
                
                case 'CITY':
                    $addr->city = trim($record[2]);
                break;
                
                case 'STAE':
                    $addr->stae = trim($record[2]);
                break;
                
                case 'POST':
                    $addr->post = trim($record[2]);
                break;
                
                case 'CTRY':
                    $addr->ctry = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $addr;
    }
}
