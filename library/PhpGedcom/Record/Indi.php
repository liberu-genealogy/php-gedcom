<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
 */

namespace PhpGedcom\Record;

/**
 *
 *
 */
class Indi extends \PhpGedcom\Record implements Noteable, Objectable, Sourceable
{
    /**
     *
     */
    protected $_id   = null;
    
    /**
     *
     */
    protected $_chan = null;
    
    /**
     *
     */
    protected $_attr = array();
    
    /**
     *
     */
    protected $_even = array();
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    protected $_obje = array();
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    protected $_name = array();
    
    /**
     *
     */
    protected $_alia = array();
    
    /**
     *
     */
    protected $_sex = null;
    
    /**
     *
     */
    protected $_rin = null;
    
    /**
     *
     */
    protected $_resn = null;
    
    /**
     *
     */
    protected $_rfn = null;
    
    /**
     *
     */
    protected $_afn = null;
    
    /**
     *
     */
    protected $_fams = array();
    
    /**
     *
     */
    protected $_famc = array();
    
    /**
     *
     */
    protected $_asso = array();
    
    /**
     *
     */
    protected $_subm = array();
    
    /**
     *
     */
    protected $_anci = array();
    
    /**
     *
     */
    protected $_desi = array();
    
    /**
     *
     */
    protected $_refn = array();
    
    /**
     *
     */
    protected $_bapl = null;
    
    /**
     *
     */
    protected $_conl = null;
    
    /**
     *
     */
    protected $_endl = null;
    
    /**
     *
     */
    protected $_slgc = null;

    /**
     *
     */
    public function addName(\PhpGedcom\Record\Indi\Name $name)
    {
        $this->_name[] = $name;
    }
    
    /**
     *
     */
    public function addAttr($attr)
    {
        $this->_attr[] = $attr;
    }
    
    /**
     *
     */
    public function addEven($even)
    {
        $this->_even[] = $even;
    }
    
    /**
     *
     */
    public function addAsso(\PhpGedcom\Record\Indi\Asso $asso)
    {
        $this->_asso[] = $asso;
    }
    
    /**
     *
     */
    public function addRefn(\PhpGedcom\Record\Refn $ref)
    {
        $this->_refn[] = $ref;
    }
    
    /**
     *
     */
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }
    
    /**
     *
     */
    public function addObje(\PhpGedcom\Record\ObjeRef $obje)
    {
        $this->_obje[] = $obje;
    }
    
    /**
     *
     */
    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
}
