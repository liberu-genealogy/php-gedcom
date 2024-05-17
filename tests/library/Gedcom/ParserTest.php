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

namespace GedcomTest;

use Gedcom\Parser;

/**
 * Class ParserTest.
 */
class ParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Gedcom\Parser
     */
    protected $parser = null;

    /**
     * @var \Gedcom\Gedcom
     */
    protected $gedcom = null;

    public function setUp(): void
    {
        $this->parser = new Parser();
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/TGC551LF.ged');
    }

    public function testNoErrors()
    {
        $ee = $this->parser->getErrors();
        $this->assertEquals(1, count($ee));
    }

    public function testRecordCounts()
    {
        $this->assertEquals(count($this->gedcom->getIndi()), 15);
        $this->assertEquals(count($this->gedcom->getFam()), 7);
        $this->assertEquals(count($this->gedcom->getSour()), 2);
        $this->assertEquals(count($this->gedcom->getNote()), 33);
        $this->assertEquals(count($this->gedcom->getObje()), 1);
        $this->assertEquals(count($this->gedcom->getRepo()), 1);
    }

    public function testHead()
    {
        $head = $this->gedcom->getHead();

        $this->assertEquals($head->getSour()->getSour(), 'GEDitCOM');
        $this->assertEquals($head->getSour()->getName(), 'GEDitCOM');
        $this->assertEquals($head->getSour()->getVers(), '2.9.4');
        $this->assertEquals($head->getSour()->getCorp()->getCorp(), 'RSAC Software');
        $this->assertEquals(
            $head->getSour()->getCorp()->getAddr()->getAddr(),
            "7108 South Pine Cone Street\nSalt Lake City, UT 84121\nUSA"


        );
        $this->assertEquals($head->getSour()->getCorp()->getAddr()->getCity(), 'Salt Lake City');
        $this->assertEquals($head->getSour()->getCorp()->getAddr()->getStae(), 'UT');
        $this->assertEquals($head->getSour()->getCorp()->getAddr()->getPost(), '84121');
        $this->assertEquals($head->getSour()->getCorp()->getAddr()->getCtry(), 'USA');

        $phon = $head->getSour()->getCorp()->getPhon();

        $this->assertEquals($phon[0], '+1-801-942-7768');
        $this->assertEquals($phon[1], '+1-801-555-1212');
        $this->assertEquals($phon[2], '+1-801-942-1148 (FAX) (last one!)');

        $this->assertEquals($head->getSour()->getData()->getData(), 'Name of source data');
        $this->assertEquals($head->getSour()->getData()->getDate(), '1 JAN 1998');
        $this->assertEquals($head->getSour()->getData()->getCopr(), 'Copyright of source data');

        $this->assertEquals($head->getSubm(), 'SUBMITTER');
        $this->assertEquals($head->getSubn(), 'SUBMISSION');

        $this->assertEquals($head->getDest(), 'ANSTFILE');

        $this->assertEquals($head->getDate()->getDate(), '1 JAN 1998');
        $this->assertEquals($head->getDate()->getTime(), '13:57:24.80');

        $this->assertEquals($head->getFile(), 'TGC55C.ged');

        $this->assertEquals($head->getGedc()->getVers(), '5.5');
        $this->assertEquals($head->getGedc()->getForm(), 'LINEAGE-LINKED');

        $this->assertEquals($head->getLang(), 'English');

        $this->assertEquals($head->getChar()->getChar(), 'ANSEL');
        $this->assertEquals($head->getChar()->getVers(), 'ANSI Z39.47-1985');

        $this->assertEquals($head->getPlac()->getForm(), 'City, County, State, Country');
        $this->assertEquals($head->getSubn(), 'SUBMISSION');
    }

    public function testSubn()
    {
        $subn = $this->gedcom->getSubn();

        $this->assertEquals($subn->getSubn(), 'SUBMISSION');
        $this->assertEquals($subn->getSubm(), 'SUBMITTER');
        $this->assertEquals($subn->getFamf(), 'NameOfFamilyFile');
        $this->assertEquals($subn->getTemp(), 'Abbreviated Temple Code');
        $this->assertEquals($subn->getAnce(), '1');
        $this->assertEquals($subn->getDesc(), '1');
        $this->assertEquals($subn->getOrdi(), 'yes');
        $this->assertEquals($subn->getRin(), '1');
    }

    public function testSubm()
    {
        $subm = $this->gedcom->getSubm();

        $this->assertEquals($subm['SUBMITTER']->getSubm(), 'SUBMITTER');
        $this->assertEquals($subm['SUBMITTER']->getName(), 'John A. Nairn');
        $this->assertEquals(
            $subm['SUBMITTER']->getAddr()->getAddr(),
            "Submitter address line 1\n" .
                "Submitter address line 2\n" .
                "Submitter address line 3\n" .
                'Submitter address line 4'
        );

        $this->assertEquals($subm['SUBMITTER']->getAddr()->getAdr1(), 'Submitter address line 1');
        $this->assertEquals($subm['SUBMITTER']->getAddr()->getAdr2(), 'Submitter address line 2');
        $this->assertEquals($subm['SUBMITTER']->getAddr()->getCity(), 'Submitter address city');
        $this->assertEquals($subm['SUBMITTER']->getAddr()->getStae(), 'Submitter address state');
        $this->assertEquals($subm['SUBMITTER']->getAddr()->getPost(), 'Submitter address ZIP code');
        $this->assertEquals($subm['SUBMITTER']->getAddr()->getCtry(), 'Submitter address country');

        $phon = $subm['SUBMITTER']->getPhon();
        $this->assertEquals($phon[0]->getPhon(), 'Submitter phone number 1');
        $this->assertEquals($phon[1]->getPhon(), 'Submitter phone number 2');
        $this->assertEquals($phon[2]->getPhon(), 'Submitter phone number 3 (last one!)');

        $lang = $subm['SUBMITTER']->getLang();
        $this->assertEquals($lang[0], 'English');
        $this->assertEquals($subm['SUBMITTER']->getChan()->getDate(), '7 Sep 2000');
        $this->assertEquals($subm['SUBMITTER']->getChan()->getTime(), '8:35:36');
        $this->assertEquals($subm['SUBMITTER']->getRfn(), 'Submitter Registered RFN');
        $this->assertEquals($subm['SUBMITTER']->getRin(), '1');

        $obje = current($subm['SUBMITTER']->getObje());
        $this->assertEquals($obje->getForm(), 'jpeg');
        $this->assertEquals($obje->getTitl(), 'Submitter Multimedia File');
        $this->assertEquals($obje->getFile(), 'ImgFile.JPG');

        $note = current($obje->getNote());
        $this->assertEquals($note->getNote(), 'N1');

        $this->assertEquals($subm['SM2']->getSubm(), 'SM2');
        $this->assertEquals($subm['SM2']->getName(), 'Secondary Submitter');
        $this->assertEquals(
            $subm['SM2']->getAddr()->getAddr(),
            "Secondary Submitter Address 1\n" .
                'Secondary Submitter Address 2'
        );

        $lang = $subm['SM2']->getLang();
        $this->assertEquals($lang[0], 'English');
        $this->assertEquals($subm['SM2']->getChan()->getDate(), '12 Mar 2000');
        $this->assertEquals($subm['SM2']->getChan()->getTime(), '10:38:33');
        $this->assertEquals($subm['SM2']->getRin(), '2');

        $this->assertEquals($subm['SM3']->getSubm(), 'SM3');
        $this->assertEquals($subm['SM3']->getName(), 'H. Eichmann');
        $this->assertEquals(
            $subm['SM3']->getAddr()->getAddr(),
            "email: h.eichmann@@mbox.iqo.uni-hannover.de\n" .
                'or: heiner_eichmann@@h.maus.de (no more than 16k!!!!)'
        );
        $this->assertEquals($subm['SM3']->getChan()->getDate(), '13 Jun 2000');
        $this->assertEquals($subm['SM3']->getChan()->getTime(), '17:07:32');
        $this->assertEquals($subm['SM3']->getRin(), '3');
    }

    // public function testIndi()
    // {
    // }

    // public function testFam()
    // {
    // }

    // public function testObje()
    // {
    // }

    // public function testRepo()
    // {
    // }

    public function testSour()
    {
        $sour = $this->gedcom->getSour();

        $secondSource = $sour['SR2'];

        $this->assertEquals($secondSource->getSour(), 'SR2');
        $this->assertEquals($secondSource->getTitl(), 'All I Know About GEDCOM, I Learned on the Internet');
        $this->assertEquals($secondSource->getAbbr(), 'What I Know About GEDCOM');
        $this->assertEquals($secondSource->getAuth(), 'Second Source Author');
        $this->assertEquals($secondSource->getChan()->getDate(), '11 Jan 2001');
        $this->assertEquals($secondSource->getChan()->getTime(), '16:21:39');
        $this->assertEquals($secondSource->getRin(), '2');
    }

    public function testNote()
    {
        $firstNote = current($this->gedcom->getNote());

        $this->assertEquals(
            $firstNote->getNote(),
            'Test link to a graphics file about the main Submitter of this file.'
        );

        $this->assertEquals($firstNote->getChan()->getDate(), '24 May 1999');
        $this->assertEquals($firstNote->getChan()->getTime(), '16:39:55');
    }



    // public function testParseValidFile()
    // {
    //     $this->gedcom = $this->parser->parse(\TEST_DIR . '/validFiles/validTest.ged');
    //     $this->assertNotNull($this->gedcom, 'Gedcom object should not be null for a valid file.');
    // }

    // public function testParseInvalidFile()
    // {
    //     $this->expectException(\Exception::class);
    //     $this->parser->parse(\TEST_DIR . '/invalidFiles/invalidTest.ged');
    // }

    // public function testForwardFunctionality()
    // {
    //     $this->parser->parse(\TEST_DIR . '/validFiles/forwardTest.ged');
    //     $this->assertEquals('2 INDI', trim($this->parser->forward()->getCurrentLine()), 'Forward method should move to the next line.');
    // }

    // public function testBackFunctionality()
    // {
    //     $this->parser->parse(\TEST_DIR . '/validFiles/backTest.ged');
    //     $this->parser->forward(); // Move forward to allow back to work
    //     $this->assertEquals('0 HEAD', trim($this->parser->back()->getCurrentLine()), 'Back method should revert to the previous line.');
    // }

    // public function testSkipToNextLevelFunctionality()
    // {
    //     $this->parser->parse(\TEST_DIR . '/validFiles/skipToNextLevelTest.ged');
    //     $this->parser->skipToNextLevel(1);
    //     $this->assertStringContainsString('1 BIRT', $this->parser->forward()->getCurrentLine(), 'SkipToNextLevel should skip to the specified level.');
    // }

    // public function testParseMultiLineRecord()
    // {
    //     $this->parser->parse(\TEST_DIR . '/validFiles/multiLineRecordTest.ged');
    //     $data = $this->parser->parseMultiLineRecord();
    //     $this->assertStringContainsString('Lorem ipsum dolor sit amet', $data, 'parseMultiLineRecord should correctly parse multi-line records.');
    // }

    // public function testEofFunctionality()
    // {
    //     $this->parser->parse(\TEST_DIR . '/validFiles/eofTest.ged');
    //     while (!$this->parser->eof()) {
    //         $this->parser->forward();
    //     }
    //     $this->assertTrue($this->parser->eof(), 'EOF should be true after reading the last line of the file.');
    // }

    // public function testErrorLogging()
    // {
    //     $this->parser->parse(\TEST_DIR . '/validFiles/errorLoggingTest.ged');
    //     $errors = $this->parser->getErrors();
    //     $this->assertNotEmpty($errors, 'Errors should be logged during parsing.');
    // }

    public function testNormalizeIdentifier()
    {
        $normalized = $this->parser->normalizeIdentifier('@I1@');
        $this->assertEquals('I1', $normalized, 'normalizeIdentifier should remove @ characters.');
    }
}
