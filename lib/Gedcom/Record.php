<?php
/**
 *
 */

namespace Gedcom;

if(!class_exists('\Gedcom\Record')):

/**
 *
 */
abstract class Record
{
    protected $_refId = null;
    
    protected $_chan = null;
    
    public $notes = array();
    public $noteRef = array();
    
    public $objects = array();
    public $objectRef = array();
    
    public $sourceCitations = array();
    public $sourceCitationRef = array();
    
    public function __set($var, $val)
    {
        if(!property_exists($this, '_' . $var))
            throw new \Exception('Unknown ' . get_class($this) . '::' . $var . ' in SET');
        
        $this->{'_' . $var} = $val;
    }
    
    public function __get($var)
    {
        if(!property_exists($this, '_' . $var))
            throw new \Exception('Unknown ' . get_class($this) . '::' . $var . ' in GET');
        
        return $this->{'_' . $var};
    }
    
    public function __call($method, $args)
    {
        if(substr($method, 0, 3) != 'add')
            throw new \Exception('Unknown method called: ' . $method);
        
        $arr = strtolower(substr($method, 3));
        
        if(!property_exists($this, '_' . $arr) || !is_array($this->{'_' . $arr}))
            throw new \Exception('Unknown ' . get_class($this) . '::' . $arr);
        
        if(!is_array($args) || !isset($args[0]))
            throw new \Exception('Incorrect arguments to ' . $method);
        
        if(is_object($args[0]))
        {
            // Type safety?
        }
        
        $this->{'_' . $arr}[] = $args[0];
    }
    
    
    /**
     *
     */
    public function addNoteRef(\Gedcom\Record\Note\Ref &$ref)
    {
        $this->noteRef[] = $ref;
    }
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\Note\Text &$note)
    {
        $this->notes[] = $note;
    }
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\Obje\Embe &$object)
    {
        $this->objects[] = $object;
    }
    
    /**
     *
     */
    public function addObjeRef(\Gedcom\Record\Obje\Ref &$object)
    {
        $this->objectRef[] = $object;
    }
    
    /**
     *
     */
    public function addSourceCitation(\Gedcom\Record\SourceCitation\Embe &$citation)
    {
        $this->sourceCitations[] = $citation;
    }
    
    /**
     *
     */
    public function addSourceCitationRef(\Gedcom\Record\SourceCitation\Ref &$citation)
    {
        $this->sourceCitationRef[] = $citation;
    }
}

endif;

