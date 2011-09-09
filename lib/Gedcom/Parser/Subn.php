<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Subn extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $subn = new \Gedcom\Record\Subn();
        $subn->id = $identifier;
        
        $parser->getGedcom()->submission = &$subn;
        
        $parser->forward();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int)$record[0];
            $recordType = strtoupper(trim($record[1]));
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'SUBM':
                    $subn->subm = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'FAMF':
                    $subn->famf = trim($record[2]);
                break;
                
                case 'TEMP':
                    $subn->temp = trim($record[2]);
                break;
                
                case 'ANCE':
                    $subn->ance = trim($record[2]);
                break;
                
                case 'DESC':
                    $subn->desc = trim($record[2]);
                break;
                
                case 'ORDI':
                    $subn->ordi = trim($record[2]);
                break;
                
                case 'RIN':
                    $subn->rin = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $subn;
    }
}
