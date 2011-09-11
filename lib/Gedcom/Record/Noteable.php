<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
interface Noteable
{
    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef &$note);
}
