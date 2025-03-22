<?php

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Record;

/**
 * Class Refn.
 */
class Refn extends \Gedcom\Record
{
    protected string $refn;
    protected string $type;

    /**
     * @param string $refn
     *
     * @return Refn
     */
    public function setRefn(string $refn = ''): self
    {
        $this->refn = $refn;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefn(): string
    {
        return $this->refn;
    }

    /**
     * @param string $type
     *
     * @return Refn
     */
    public function setType(string $type = ''): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}