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

namespace PhpGedcom\Record\ObjeRef;

use PhpGedcom\Record;

/**
 * Class Refn
 * @package PhpGedcom\Record
 */
class File extends Record
{
    /**
     * @var string multimedia_file_refn
     */
    protected $file;

    /**
     * @var PhpGedcom\Record\ObjeRef\File\Form
     */
    protected $form;
    protected $titl;

}
