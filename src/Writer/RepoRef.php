<?php

declare(strict_types=1);

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

final class RepoRef
{
    /**
     * @param \Gedcom\Record\RepoRef $reporef
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\RepoRef $reporef, int $level): string
    {
        $repo = $reporef->getRepo();
        if (empty($repo)) {
            return '';
        }

        $output = sprintf('%d REPO %s\n', $level, $repo);
        $level++;

        foreach ($reporef->getNote() as $note) {
            $output .= NoteRef::convert($note, $level);
        }

        foreach ($reporef->getCaln() as $caln) {
            $output .= Caln::convert($caln, $level);
        }

        return $output;
    }
}