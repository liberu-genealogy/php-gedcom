<?php
/**
 *
 */

namespace Gedcom\Parser\Indi\Even;

/**
 *
 *
 */
class Place extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $plac = new \Gedcom\Record\Indi\Even\Place();
        $plac->place = isset($record[2]) ? trim($record[2]) : null;
        
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
                case 'FORM':
                    $plac->form = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $plac->addNote($note);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $plac->addSour($sour);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $plac;
    }
}
