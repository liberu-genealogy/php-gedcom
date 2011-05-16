<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Address extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $address = new \Gedcom\Record\Address();
        
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
                    $address->addr .= "\n";
                    if(isset($record[2]))
                        $address->addr .= trim($record[2]);
                break;
                
                case 'ADR1':
                    $address->adr1 = trim($record[2]);
                break;
                
                case 'ADR2':
                    $address->adr2 = trim($record[2]);
                break;
                
                case 'CITY':
                    $address->city = trim($record[2]);
                break;
                
                case 'STAE':
                    $address->stae = trim($record[2]);
                break;
                
                case 'POST':
                    $address->post = trim($record[2]);
                break;
                
                case 'CTRY':
                    $address->ctry = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $address;
    }
}
