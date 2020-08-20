<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @package         php-gedcom
 * @license         GPL-3.0
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Record\Plac;

use PhpGedcom\Record;

/**
 * Class Refn
 * @package PhpGedcom\Record
 */
class Fone extends Record
{
    /**
     * @var string phonetic_variation
     */
    protected $_fone;

    /**
     * @var string phonetic_type
     */
    protected $_type;
}
