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

namespace Gedcom\Record;

/**
 *
 */
class Head extends \Gedcom\Record
{
    protected $_sour = null;
    protected $_dest = null;
    protected $_date = null;
    protected $_subm = null;
    protected $_subn = null;
    protected $_file = null;
    protected $_copr = null;
    protected $_gedc = null;
    protected $_char = null;
    protected $_lang = null;
    protected $_plac = null;
    protected $_note = null;
}

