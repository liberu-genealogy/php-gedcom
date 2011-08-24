<?php

namespace Gedcom;

require_once __DIR__ . '/Record/Indi.php';
require_once __DIR__ . '/Record/Fam.php';
require_once __DIR__ . '/Record/Sour.php';
require_once __DIR__ . '/Record/Note.php';
require_once __DIR__ . '/Record/Data.php';
require_once __DIR__ . '/Record/Chan.php';
require_once __DIR__ . '/Record/Obje.php';
require_once __DIR__ . '/Parser.php';

use Gedcom\Record\Indi;
use Gedcom\Record\Fam;
use Gedcom\Record\Sour;
use Gedcom\Record\Note;
use Gedcom\Record\Data;
use Gedcom\Record\Note\Text;

/**
 *
 *
 */
class Gedcom
{
    protected $_head = null;
    protected $_subn = null;
    
    protected $_sour = array();
    protected $_indi = array();
    protected $_fam = array();
    protected $_note = array();
    protected $_repos = array();
    protected $_obje = array();
    protected $_subm = array();
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\Sour $sour)
    {
        $this->_sour[$sour->refId] = $sour;
    }
    
    /**
     *
     */
    public function addIndi(\Gedcom\Record\Indi &$indi)
    {
        $this->_indi[$indi->refId] = &$indi;
    }
    
    /**
     *
     *
     */
    public function addFam(\Gedcom\Record\Fam &$fam)
    {
        $this->_fam[$fam->refId] = &$fam;
    }
    
    /**
     *
     *
     */
    public function addNote(\Gedcom\Record\Note &$note)
    {
        $this->_note[$note->refId] = &$note;
    }
    
    /**
     *
     */
    public function addRepo(\Gedcom\Record\Repo &$repo)
    {
        $this->_repo[$repo->refId] = &$repo;
    }
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\Obje &$obje)
    {
        $this->_obje[$obje->refId] = &$obje;
    }
    
    /**
     *
     */
    public function addSubm(\Gedcom\Record\Subm &$subm)
    {
        $this->_subm[$subm->refId] = &$subm;
    }
}
