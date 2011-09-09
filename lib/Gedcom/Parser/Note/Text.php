<?php
/**
 *
 */

namespace Gedcom\Parser\Note;

/**
 *
 *
 */
class Text extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $text = new \Gedcom\Record\Note\Text();
        $text->note = $record[2];
        
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
                case 'CONT':
                    $text->note .= "\n";
                    
                    if(isset($record[2]))
                        $text->note .= trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $text->note .= ' ' . trim($record[2]);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $text->addSourceCitationRef($citation);
                    else
                        $text->addSourceCitation($citation);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $text;
    }
}
