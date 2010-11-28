<?php

namespace Gedcom;

require_once __DIR__ . '/Record/Person.php';
require_once __DIR__ . '/Record/Family.php';
require_once __DIR__ . '/Record/Source.php';
require_once __DIR__ . '/Record/Note.php';
require_once __DIR__ . '/Record/Note/Reference.php';
require_once __DIR__ . '/Record/Reference.php';
require_once __DIR__ . '/Record/ReferenceNumber.php';
require_once __DIR__ . '/Record/Data.php';
require_once __DIR__ . '/Record/Change.php';
require_once __DIR__ . '/Record/Object.php';
require_once __DIR__ . '/Parser.php';

use Gedcom\Record\Person;
use Gedcom\Record\Family;
use Gedcom\Record\Source;
use Gedcom\Record\Note;
use Gedcom\Record\Reference;
use Gedcom\Record\Data;

/**
 *
 *
 */
class Gedcom
{
    public $sources = array();
    public $people = array();
    public $families = array();
    public $notes = array();
    
    /**
     *
     */
    public function &createSource($identifier)
    {
        $this->sources[$identifier] = new Source();
        $this->sources[$identifier]->refId = $identifier;
        
        return $this->sources[$identifier];
    }
    
    
    /**
     *
     */
    public function &createPerson($identifier)
    {
        $this->people[$identifier] = new Person();
        $this->people[$identifier]->refId = $identifier;
        
        return $this->people[$identifier];
    }
    
    
    /**
     *
     */
    public function &createReference($identifier, $for)
    {
        $reference = new Reference();
        $reference->sourceId = $identifier;
        $reference->attribute = $for;
        
        return $reference;
    }
    
    
    /**
     *
     *
     */
    public function &createFamily($identifier)
    {
        $family = new Family();
        $family->refId = $identifier;
        
        $this->families[$identifier] = $family;
        
        return $family;
    }
    
    
    /**
     *
     *
     */
    public function &createNote($identifier = null)
    {
        $note = new Note();
        $note->refId = $identifier;
        
        $this->notes[] = $note;
        
        return $note;
    }
    
    
    /**
     *
     *
     */
    public function &findPerson($identifier)
    {
        if(isset($this->people[$identifier]))
            return $this->people[$identifier];
        
        return null;
    }
    
    
    /**
     *
     */
    public static function parseFile($fileName)
    {
        $parser = new Parser();
        $gedcom = $parser->parseFile($fileName);
        
        echo '<pre>' . print_r($parser->getErrors(), true) . '</pre>';
        
        return $gedcom;
    }
}
