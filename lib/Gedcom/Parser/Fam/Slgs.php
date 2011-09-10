<?php
/**
 *
 */

namespace Gedcom\Parser\Fam;

/**
 *
 *
 */
class Slgs extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $slgs = new \Gedcom\Record\Fam\Slgs();
        
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
                case 'STAT':
                    $slgs->stat = trim($record[2]);
                break;
                
                case 'DATE':
                    $slgs->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $slgs->plac = trim($record[2]);
                break;
                
                case 'TEMP':
                    $slgs->temp = trim($record[2]);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $slgs->addSour($sour);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $slgs->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $slgs;
    }
}
