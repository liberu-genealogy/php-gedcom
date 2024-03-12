&lt;?php

namespace Gedcom\Parser\Interfaces;

interface ParserInterface
{
    public function parse($fileName);

    public function forward();

    public function back();

    public function eof();
}
