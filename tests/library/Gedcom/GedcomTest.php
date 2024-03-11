&lt;?php

namespace GedcomTest\library\Gedcom;

use Gedcom\Gedcom;
use Gedcom\Record\Head;
use Gedcom\Record\Subn;
use Gedcom\Record\Sour;
use Gedcom\Record\Indi;
use Gedcom\Record\Fam;
use Gedcom\Record\Note;
use Gedcom\Record\Repo;
use Gedcom\Record\Obje;
use Gedcom\Record\Subm;
use PHPUnit\Framework\TestCase;

class GedcomTest extends TestCase
{
    public function headProvider()
    {
        return [
            [new Head()],
        ];
    }

    /**
     * @dataProvider headProvider
     */
    public function testSetAndGetHead($head)
    {
        $gedcom = new Gedcom();
        $gedcom->setHead($head);
        $this->assertEquals($head, $gedcom->getHead());
    }

    public function subnProvider()
    {
        return [
            [new Subn()],
        ];
    }

    /**
     * @dataProvider subnProvider
     */
    public function testSetAndGetSubn($subn)
    {
        $gedcom = new Gedcom();
        $gedcom->setSubn($subn);
        $this->assertEquals($subn, $gedcom->getSubn());
    }

    public function sourProvider()
    {
        return [
            [new Sour(), 'S1'],
            [new Sour(), 'S2'],
        ];
    }

    /**
     * @dataProvider sourProvider
     */
    public function testAddAndGetSour($sour, $id)
    {
        $gedcom = new Gedcom();
        $sour->setSour($id);
        $gedcom->addSour($sour);
        $this->assertEquals($sour, $gedcom->getSour()[$id]);
    }

    public function indiProvider()
    {
        $indi1 = $this->createMock(Indi::class);
        $indi1->method('getId')->willReturn('I1');
        $indi2 = $this->createMock(Indi::class);
        $indi2->method('getId')->willReturn('I2');
        return [
            [$indi1, 'I1'],
            [$indi2, 'I2'],
        ];
    }

    /**
     * @dataProvider indiProvider
     */
    public function testAddAndGetIndi($indi, $id)
    {
        $gedcom = new Gedcom();
        $gedcom->addIndi($indi);
        $this->assertEquals($indi, $gedcom->getIndi()[$id]);
    }

    // Similar structure for other methods like addFam, addNote, addRepo, addObje, addSubm, and their getters.

}
