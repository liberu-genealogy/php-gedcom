<?php

namespace Gedcom\Parser\Header;

/**
 *
 *
 */
class Source extends \Gedcom\Parser\Component
{
    /*
    +1 SOUR <APPROVED_SYSTEM_ID>  {1:1}
      +2 CORP <NAME_OF_BUSINESS>  {0:1}
        +3 <<ADDRESS_STRUCTURE>>  {0:1}
      +2 DATA <NAME_OF_SOURCE_DATA>  {0:1}
        +3 DATE <PUBLICATION_DATE>  {0:1}
        +3 COPR <COPYRIGHT_SOURCE_DATA>  {0:1}
    */
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $source = new \Gedcom\Record\Header\Source();
        $source->source = trim($record[2]);
        
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
                    $source->version = trim($record[2]);
                break;
                
                case 'NAME':
                    $source->name = trim($record[2]);
                break;
                
                case 'CORP':
                    $source->corp = \Gedcom\Parser\Header\Source\Corp::parse($parser);
                break;
                
                case 'DATA':
                    $source->data = \Gedcom\Parser\Header\Source\Data::parse($parser);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $source;
    }
}
