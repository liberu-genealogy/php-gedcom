<?php
/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Xiang Ming <wenqiangliu344@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Xiang Ming
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Writer;

class RepoRef
{
    /**
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\RepoRef &$reporef, $level)
    {
        $output = '';
        $_repo = $reporef->getRepo();
        if (empty($_sour)) {
            return $output;
        } else {
            $output .= $level.' REPO '.$_repo."\n";
        }
        // level up
        $level++;

        // Note array
        $note = $reporef->getNote();
        if (!empty($note) && (is_countable($note) ? count($note) : 0) > 0) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // _caln array
        $_caln = $reporef->getCaln();
        if (!empty($_caln) && (is_countable($_caln) ? count($_caln) : 0) > 0) {
            foreach ($_caln as $item) {
                $_convert = \Gedcom\Writer\Caln::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
