<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
interface Sourceable
{
    /**
     *
     */
    public function addSour(\Gedcom\Record\SourRef &$sour);
}
