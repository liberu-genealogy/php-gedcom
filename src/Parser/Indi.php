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

namespace Gedcom\Parser;

class Indi extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $indi = new \Gedcom\Record\Indi();
        $indi->setId($identifier);

        $parser->getGedcom()->addIndi($indi);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            if ($recordType == 'BURI') {
                $a = '';
            }

            switch ($recordType) {
            case '_UID':
                $indi->setUid(trim((string) $record[2]));
                break;
            case 'RESN':
                $indi->setResn(trim((string) $record[2]));
                break;
            case 'NAME':
                $name = \Gedcom\Parser\Indi\Name::parse($parser);
                $indi->addName($name);
                break;
            case 'SEX':
                $indi->setSex(isset($record[2]) ? trim((string) $record[2]) : '');
                break;
            case 'ADOP':
            case 'BIRT':
                $birt = \Gedcom\Parser\Birt::parse($parser);
                $indi->setBirt($birt);
                break;
            case 'BAPM':
            case 'BARM':
            case 'BASM':
            case 'BLES':
            case 'BURI':
                $buri = \Gedcom\Parser\Buri::parse($parser);
                $indi->setBuri($buri);
                break;
            case 'CENS':
            case 'CHR':
                $chr = \Gedcom\Parser\Chr::parse($parser);
                $indi->setChr($chr);
                break;
            case 'CHRA':
            case 'CONF':
            case 'CREM':
            case 'DEAT':
                $deat = \Gedcom\Parser\Deat::parse($parser);
                $indi->setDeat($deat);
                break;
            case 'EMIG':
            case 'FCOM':
            case 'GRAD':
            case 'IMMI':
            case 'NATU':
            case 'ORDN':
            case 'RETI':
            case 'PROB':
            case 'WILL':
            case 'EVEN':
                $className = ucfirst(strtolower($recordType));
                $class = '\\Gedcom\\Parser\\Indi\\'.$className;

                $event = $class::parse($parser);
                $indi->addEven($event);
                break;
            case 'CAST':
            case 'DSCR':
            case 'EDUC':
            case 'IDNO':
            case 'NATI':
            case 'NCHI':
            case 'NMR':
            case 'OCCU':
            case 'PROP':
            case 'RELI':
            case 'RESI':
            case 'SSN':
            case 'TITL':
                $className = ucfirst(strtolower($recordType));
                $class = '\\Gedcom\\Parser\\Indi\\'.$className;

                $att = $class::parse($parser);
                $indi->addAttr($att);
                break;
            case 'BAPL':
            case 'CONL':
            case 'ENDL':
            case 'SLGC':
                $className = ucfirst(strtolower($recordType));
                $class = '\\Gedcom\\Parser\\Indi\\'.$className;

                $lds = $class::parse($parser);
                $indi->{'add'.$recordType}[] = $lds;
                break;
            case 'FAMC':
                $famc = \Gedcom\Parser\Indi\Famc::parse($parser);
                if ($famc) {
                    $indi->addFamc($famc);
                }
                break;
            case 'FAMS':
                $fams = \Gedcom\Parser\Indi\Fams::parse($parser);
                if ($fams) {
                    $indi->addFams($fams);
                }
                break;
            case 'SUBM':
                $indi->addSubm($parser->normalizeIdentifier($record[2]));
                break;
            case 'ASSO':
                $asso = \Gedcom\Parser\Indi\Asso::parse($parser);
                $indi->addAsso($asso);
                break;
            case 'ALIA':
                $indi->addAlia($parser->normalizeIdentifier($record[2]));
                break;
            case 'ANCI':
                $indi->addAnci($parser->normalizeIdentifier($record[2]));
                break;
            case 'DESI':
                $indi->addDesi($parser->normalizeIdentifier($record[2]));
                break;
            case 'RFN':
                $indi->setRfn(trim((string) $record[2]));
                break;
            case 'AFN':
                $indi->setAfn(trim((string) $record[2]));
                break;
            case 'REFN':
                $ref = \Gedcom\Parser\Refn::parse($parser);
                $indi->addRefn($ref);
                break;
            case 'RIN':
                $indi->setRin(trim((string) $record[2]));
                break;
            case 'CHAN':
                $chan = \Gedcom\Parser\Chan::parse($parser);
                $indi->setChan($chan);
                break;
            case 'NOTE':
                $note = \Gedcom\Parser\NoteRef::parse($parser);
                if ($note) {
                    $indi->addNote($note);
                }
                break;
            case 'SOUR':
                $sour = \Gedcom\Parser\SourRef::parse($parser);
                $indi->addSour($sour);
                break;
            case 'OBJE':
                $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                $indi->addObje($obje);
                break;
            default:
                $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $indi;
    }
}
