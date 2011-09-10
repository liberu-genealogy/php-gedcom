<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Famc extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $famc = $parser->normalizeIdentifier($record[2]);
        
        $fam = new \Gedcom\Record\Indi\Famc();
        $fam->famc = $famc;
        
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
                case 'PEDI':
                    $fam->pedi = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $fam->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $fam;
    }
}
