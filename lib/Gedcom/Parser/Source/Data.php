<?php
/**
 *
 */

namespace Gedcom\Parser\Source;

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
        
        $data = new \Gedcom\Record\Source\Data();
        
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
                case 'EVEN':
                    $data->events[] = \Gedcom\Parser\Source\Data\Event::parse($parser);
                break;
                
                case 'DATE':
                    $data->date = trim($record[2]);
                break;
                
                case 'AGNC':
                    $data->agnc = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $data->addNote($note);
                break;
                
                case 'TEXT':
                    $data->addText($parser->parseMultiLineRecord());
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $data;
    }
}
