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
                $identifier = $this->normalizeIdentifier($record[1]);
               
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
                    Parser\Family::parse($this);
                }
                else if(isset($record[2]) && $record[2] == 'NOTE')
                {
                    Parser\Note::parse($this);
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
}

