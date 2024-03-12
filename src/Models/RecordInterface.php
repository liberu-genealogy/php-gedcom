&lt;?php

namespace Gedcom\Models;

interface RecordInterface
{
    public function getId();
    public function setId($id);
    public function getName();
    public function setName($name);
}
