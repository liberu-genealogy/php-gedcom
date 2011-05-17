<?php

namespace Gedcom;

require_once __DIR__ . '/Gedcom.php';
require_once __DIR__ . '/Parser/Base.php';
require_once __DIR__ . '/Parser/Header.php';
require_once __DIR__ . '/Parser/Submission.php';
require_once __DIR__ . '/Parser/Submitter.php';
require_once __DIR__ . '/Parser/Source.php';
require_once __DIR__ . '/Parser/Object.php';
require_once __DIR__ . '/Parser/Note.php';
require_once __DIR__ . '/Parser/Individual.php';
require_once __DIR__ . '/Parser/Family.php';

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
        
        while($this->getCurrentLine() < $this->getFileLength())
        {
            $record = $this->getCurrentLineRecord();
            $depth = (int)$record[0];
            // We only process 0 level records here. Sub levels are processed
            // in methods for those data types (individuals, sources, etc)
            
            if($depth == 0)
            {
                // Although not always an identifier (HEAD,TRLR):
                $identifier = $this->normalizeIdentifier($record[1]);
               
                if(trim($record[1]) == 'HEAD')
                {
                    Parser\Header::parse($this);
                }
                else if(isset($record[2]) && trim($record[2]) == 'SUBN')
                {
                    Parser\Submission::parse($this);
                }
                else if(isset($record[2]) && trim($record[2]) == 'SUBM')
                {
                    Parser\Submitter::parse($this);
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
                else if(isset($record[2]) && $record[2] == 'REPO')
                {
                    Parser\Repo::parse($this);
                }
                else if(isset($record[2]) && $record[2] == 'OBJE')
                {
                    Parser\Object::parse($this);
                }
                else if(trim($record[1]) == 'TRLR')
                {
                    // EOF
                    break;
                }
                else
                {
                    $this->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                }
            }
            else
            {
                $this->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $this->forward();
        }
        
        return $this->getGedcom();
    }
}

