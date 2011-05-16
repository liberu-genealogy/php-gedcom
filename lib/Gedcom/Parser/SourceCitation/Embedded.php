<?php

namespace Gedcom\Parser\SourceCitation;

/**
 *
 *
 */
class Embedded extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $embedded = new \Gedcom\Record\SourceCitation\Embedded();
        $embedded->text = $record[2];
        
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
                    $embedded->text .= "\n";
                    
                    if(isset($record[2]))
                        $embedded->text .= trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $embedded->text .= ' ' . trim($record[2]);
                break;
            
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $embedded;
    }
}
