<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class LdsIndividualOrdinance extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        
        
        $ldsIndividualOrdinance = null;
        
        switch(strtoupper($record[1]))
        {
            case 'BAPL':
                $ldsIndividualOrdinance = new \Gedcom\Record\Indi\LdsIndividualOrdinance\Bapl();
            break;
            
            case 'CONL':
                $ldsIndividualOrdinance = new \Gedcom\Record\Indi\LdsIndividualOrdinance\Conl();
            break;
            
            case 'ENDL':
                $ldsIndividualOrdinance = new \Gedcom\Record\Indi\LdsIndividualOrdinance\Endl();
            break;
            
            case 'SLGC':
                $ldsIndividualOrdinance = new \Gedcom\Record\Indi\LdsIndividualOrdinance\Slgc();
            break;
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
                case 'STAT':
                    $ldsIndividualOrdinance->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $ldsIndividualOrdinance->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $ldsIndividualOrdinance->plac = trim($record[2]);
                break;
                
                case 'TEMP':
                    $ldsIndividualOrdinance->temp = trim($record[2]);
                break;
                
                case 'FAMC':
                    $ldsIndividualOrdinance->famc = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $ldsIndividualOrdinance->addSour($sour);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $ldsIndividualOrdinance->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $ldsIndividualOrdinance;
    }
}
