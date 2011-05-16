<?php

namespace Gedcom;

require_once __DIR__ . '/Parser/Base.php';
require_once __DIR__ . '/Gedcom.php';
require_once __DIR__ . '/Parser/Object.php';
require_once __DIR__ . '/Parser/Individual.php';

/**
 *
 *
 */
class Parser extends Parser\Base
{
    
    /**
     *
     *
     */
    public function parse($fileName)
    {
        $contents = file_get_contents($fileName);
        
        $this->_file = explode("\n", mb_convert_encoding($contents, 'UTF-8'));
        
        $this->_gedcom = new Gedcom();
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            // We only process 0 level records here. Sub levels are processed
            // in methods for those data types (individuals, sources, etc)
            
            if((int)$record[0] == 0)
            {
                // Although not always an identifier (HEAD,TRLR):
                $identifier = trim(trim($record[1], '@'));
               
                if(trim($record[1]) == 'HEAD')
                {
                    // TODO
                }
                else if(isset($record[2]) && trim($record[2]) == 'SUBN')
                {
                    // TODO SUBMISSION
                }
                else if(isset($record[2]) && trim($record[2]) == 'SUBM')
                {
                    // TODO SUBMITER
                }
                else if(trim($record[1]) == 'TRLR')
                {
                    // EOF
                    break;
                }
                else if(isset($record[2]) && $record[2] == 'SOUR')
                {
                    Parser\Source::parse($this);
                }
                else if(isset($record[2]) && $record[2] == 'INDI')
                {
                    Parser\Individual::parse($this);
                }
                else if(isset($record[2]) && $record[2] == 'FAM')
                {
                    $family = $this->_gedcom->createFamily($identifier);
                    $this->parseFamily($family);
                }
                else if(isset($record[2]) && $record[2] == 'NOTE')
                {
                    $note = $this->_gedcom->createNote($identifier);
                    $this->parseNote($note);
                }
                else
                {
                    // FIXME
                    //$this->logUnhandledRecord(__LINE__);
                }
            }
            
            $this->_currentLine++;
        }
        
        return $this->_gedcom;
    }
    
    
    /**
     *
     *
     */
    protected function parseFamily(&$family)
    {
        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if($record[0] == '0')
            {
                $this->_currentLine--;
                break;
            }
            else if($record[0] == '1')
            {
                $recordType = trim($record[1]);
                
                $familyId = trim(trim($record[1]), '@F');
                
                switch($recordType)
                {
                    case 'HUSB':
                        $family->husbandId = trim(trim($record[2]), '@I');
                    break;    
                    
                    case 'WIFE':
                        $family->wifeId = trim(trim($record[2]), '@I');
                    break;
                    
                    case 'CHIL':
                        $family->children[] = trim(trim($record[2]), '@I');
                    break;
                    /*
                     FIXME
                    case 'MARR':
                        $this->parseEventRecord($family, 'marriage');
                    break;
                    
                    case 'DIV':
                        $this->parseEventRecord($family, 'divorce');
                    break;
                    */
                    
                    case 'RIN':
                        $family->rin = trim($record[2]);
                    break;
                    
                    case 'NOTE':
                        $family->notes[] = trim(trim($record[2]), '@N');
                    break;
                
                    case 'CHAN':
                        $change = \Gedcom\Parser\Change::parse($this);
                        $family->change = $change;
                    break;
                    
                    default:
                        // FIXME
                        //$this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                // FIXME
                //$this->logUnhandledRecord(__LINE__);
            }
            
            $this->_currentLine++;
        }
    }
    
    
    /**
     *
     */
    protected function parseNote(&$note)
    {
        $record = $this->getCurrentLineRecord();

        $startLevel = $record[0];

        if($startLevel > 0)
        {
            //$if(isset($record[2]) && preg_match
        }

        $this->_currentLine++;
        
        while($this->_currentLine < count($this->_file))
        {
            $record = $this->getCurrentLineRecord();
            
            if((int)$record[0] <= 0)
            {
                $this->_currentLine--;
                break;
            }
            else if((int)$record[0] > 0)
            {
                $recordType = trim($record[1]);
                
                switch($recordType)
                {
                    case 'RIN':
                        $note->rin = trim($record[2]);
                    break;
                    
                    case 'CONT':
                        if(isset($record[2]))
                            $note->note .= "\n" . $record[2];
                    break;
                    
                    case 'CONC':
                        if(isset($record[2]))
                            $note->note .= $record[2];
                    break;
                   
                    case 'REFN':
                        $reference = \Gedcom\Parser\ReferenceNumber::parse($this);
                        $note->addReferenceNumber($reference);
                    break;
                    
                    case 'CHAN':
                        $change = \Gedcom\Parser\Change::parse($this);
                        $note->change = &$change;
                    break;

                    case 'SOUR':
                        // FIXME    
                        //$source = new \Gedcom\Record\Source();

                        //$this->parseSource($source);

                        //$note->sources[] = $source;
                    break;
                    
                    default:
                        // FIXME
                        //$this->logUnhandledRecord(__LINE__);
                }
            }
            /*else if((int)$record[0] > 0)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                // FIXME
                //$this->logUnhandledRecord(__LINE__);
            }
            
            $this->_currentLine++;
        }
    }

}

