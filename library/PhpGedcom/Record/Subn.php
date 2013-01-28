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
 */
class Subn extends \PhpGedcom\Record
{
    protected $_id      = null;
    protected $_chan    = null;
    
    protected $_subm    = null;
    protected $_famf    = null;
    protected $_temp    = null;
    protected $_ance    = null;
    protected $_desc    = null;
    protected $_ordi    = null;
    protected $_rin     = null;
}

