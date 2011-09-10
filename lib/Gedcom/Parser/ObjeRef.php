<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class ObjeRef extends \Gedcom\Parser\Component
{
    /**
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $obje = new \Gedcom\Record\ObjeRef();
        
        if(isset($record[2]))
        {
            $obje->setIsReference(true);
            $obje->obje = $parser->normalizeIdentifier($record[2]);
        }
        else
        {
            $obje->setIsReference(false);
        }
        
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
                case 'TITL':
                    $obje->title = trim($record[2]);
                break;
                
                case 'FILE':
                    $obje->file = trim($record[2]);
                break;
                
                case 'FORM':
                    $obje->form = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $obje->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $obje;
    }
}
