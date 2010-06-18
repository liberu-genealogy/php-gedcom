<?php

namespace Gedcom;

require_once __DIR__ . '/Record/Person.php';
require_once __DIR__ . '/Record/Source.php';
require_once __DIR__ . '/Record/Reference.php';
require_once __DIR__ . '/Record/Data.php';
require_once __DIR__ . '/Record/Event.php';
require_once __DIR__ . '/Parser.php';

use Gedcom\Record\Person;
use Gedcom\Record\Source;
use Gedcom\Record\Reference;
use Gedcom\Record\Data;

class Gedcom
{
    public $sources = array();
    public $people = array();
    
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
     */
    public static function parseFile($fileName)
    {
        $parser = new Parser();
        $gedcom = $parser->parseFile($fileName);
        
        echo '<pre>' . print_r($parser->getErrors(), true) . '</pre>';
        
        return $gedcom;
    }
}
