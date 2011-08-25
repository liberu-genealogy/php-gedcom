<?php
/**
 *
 */

namespace Gedcom\Parser\Head;

/**
 *
 *
 */
class Sour extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $source = new \Gedcom\Record\Head\Sour();
        $source->sour = trim($record[2]);
        
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
                case 'VERS':
                    $source->vers = trim($record[2]);
                break;
                
                case 'NAME':
                    $source->name = trim($record[2]);
                break;
                
                case 'CORP':
                    $source->corp = \Gedcom\Parser\Head\Sour\Corp::parse($parser);
                break;
                
                case 'DATA':
                    $source->data = \Gedcom\Parser\Head\Sour\Data::parse($parser);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $source;
    }
}
