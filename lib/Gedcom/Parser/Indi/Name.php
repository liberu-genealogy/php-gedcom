<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Name extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $name = new \Gedcom\Record\Indi\Name();
        $name->name = trim($record[2]);
        
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
                case 'NPFX':
                    $name->npfx = trim($record[2]);
                break;
                
                case 'GIVN':
                    $name->givn = trim($record[2]);
                break;
                
                case 'NICK':
                    $name->nick = trim($record[2]);
                break;
                
                case 'SPFX':
                    $name->spfx = trim($record[2]);
                break;
                
                case 'SURN':
                    $name->surn = trim($record[2]);
                break;
                
                case 'NSFX':
                    $name->nsfx = trim($record[2]);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $name->addSour($sour);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $name->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $name;
    }
}
