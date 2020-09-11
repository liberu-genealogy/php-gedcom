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

namespace PhpGedcom\Writer;

class RepoRef
{
    /**
     * @param \PhpGedcom\Record\RepoRef $reporef
     * @param int                       $level
     *
     * @return string
     */
    public static function convert(\PhpGedcom\Record\RepoRef &$reporef, $level)
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
        if (!empty($note) && count($note) > 0) {
            foreach ($note as $item) {
                $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // _caln array
        $_caln = $reporef->getCaln();
        if (!empty($_caln) && count($_caln) > 0) {
            foreach ($_caln as $item) {
                $_convert = \PhpGedcom\Writer\Caln::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
