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

namespace Gedcom\Record\Indi;

use \Gedcom\Record\Sourceable;
use \Gedcom\Record\Noteable;

/**
 *
 */
class Asso extends \Gedcom\Record implements Sourceable, Noteable
{
    protected $_indi = null;
    protected $_rela = null;
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
}
