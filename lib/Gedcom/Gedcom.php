<?php

namespace Gedcom;

require_once __DIR__ . '/Record/Individual.php';
require_once __DIR__ . '/Record/Family.php';
require_once __DIR__ . '/Record/Source.php';
require_once __DIR__ . '/Record/Note.php';
require_once __DIR__ . '/Record/Data.php';
require_once __DIR__ . '/Record/Chan.php';
require_once __DIR__ . '/Record/Object.php';
require_once __DIR__ . '/Parser.php';

use Gedcom\Record\Individual;
use Gedcom\Record\Family;
use Gedcom\Record\Source;
use Gedcom\Record\Note;
use Gedcom\Record\Data;
use Gedcom\Record\Note\Text;

/**
 *
 *
 */
class Gedcom
{
    public $head = null;
    public $submission = null;
    
    public $sources = array();
    public $individuals = array();
    public $families = array();
    public $notes = array();
    public $repos = array();
    public $objects = array();
    public $submitters = array();
    
    /**
     *
     */
    public function addSource(\Gedcom\Record\Source $source)
    {
        $this->sources[$source->refId] = $source;
    }
    
    /**
     *
     */
    public function addIndividual(\Gedcom\Record\Individual &$indi)
    {
        $this->individual[$indi->refId] = &$indi;
    }
    
    /**
     *
     *
     */
    public function addFamily(\Gedcom\Record\Family &$family)
    {
        $this->families[$family->refId] = &$family;
    }
    
    /**
     *
     *
     */
    public function addNote(\Gedcom\Record\Note &$note)
    {
        $this->notes[$note->refId] = &$note;
    }
    
    /**
     *
     */
    public function addRepo(\Gedcom\Record\Repo &$repo)
    {
        $this->repos[$repo->refId] = &$repo;
    }
    
    /**
     *
     */
    public function addObject(\Gedcom\Record\Object &$object)
    {
        $this->objects[$object->refId] = &$object;
    }
    
    /**
     *
     */
    public function addSubm(\Gedcom\Record\Subm &$subm)
    {
        $this->submitters[$subm->refId] = &$subm;
    }
    
    /**
     *
     *
     */
    public function &findIndividual($identifier)
    {
        if(isset($this->individuals[$identifier]))
            return $this->individuals[$identifier];
        
        return null;
    }
}
