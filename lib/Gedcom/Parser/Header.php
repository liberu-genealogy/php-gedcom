<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Header extends \Gedcom\Parser\Component
{

    
/*
    +1 SOUR <APPROVED_SYSTEM_ID>  {1:1}
      +2 VERS <VERSION_NUMBER>  {0:1}
      +2 NAME <NAME_OF_PRODUCT>  {0:1}
      +2 CORP <NAME_OF_BUSINESS>  {0:1}
        +3 <<ADDRESS_STRUCTURE>>  {0:1}
      +2 DATA <NAME_OF_SOURCE_DATA>  {0:1}
        +3 DATE <PUBLICATION_DATE>  {0:1}
        +3 COPR <COPYRIGHT_SOURCE_DATA>  {0:1}
    +1 DATE <TRANSMISSION_DATE>  {0:1}
      +2 TIME <TIME_VALUE>  {0:1}
    +1 GEDC        {1:1}
      +2 VERS <VERSION_NUMBER>  {1:1}
      +2 FORM <GEDCOM_FORM>  {1:1}
    +1 CHAR <CHARACTER_SET>  {1:1}
      +2 VERS <VERSION_NUMBER>  {0:1}
    +1 PLAC        {0:1}
      +2 FORM <PLACE_HIERARCHY>  {1:1}
*/

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $head = new \Gedcom\Record\Submission();
        
        $parser->getGedcom()->header = &$head;
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
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
                case 'SOUR':
                    $source = \Gedcom\Parser\Header\Source::parse($parser);
                    $head->source = &$source;
                break;
                
                case 'DEST':
                    $head->dest = trim($record[2]);
                break;
                
                case 'SUBM':
                    $head->subm = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'SUBN':
                    $head->subn = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'DEST':
                    $head->dest = trim($record[2]);
                break;
                
                case 'FILE':
                    $head->file = trim($record[2]);
                break;
                
                case 'COPR':
                    $head->copr = trim($record[2]);
                break;
                
                case 'LANG':
                    $head->lang = trim($record[2]);
                break;
                
                case 'NOTE':
                    $head->note = $parser->parseMultiLineRecord();
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $head;
    }
}
