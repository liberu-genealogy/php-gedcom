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
    public static function convert(\Gedcom\Record\RepoRef $reporef, int $level): string
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
        $notes = $reporef->getNote();
        if (!empty($notes) && count($notes) > 0) {
            foreach ($notes as $item) {
                $output .= \Gedcom\Writer\NoteRef::convert($item, $level);
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