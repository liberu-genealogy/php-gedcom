&lt;?php

namespace Gedcom\Models;

/**
 * Interface for GEDCOM record models.
 *
 * Defines the essential operations that all GEDCOM record types must implement, 
 * such as getting and setting the ID and name.
 */
interface RecordInterface
{
    public function getId();
    public function setId($id);
    public function getName();
    public function setName($name);
}
/**
 * Retrieves the ID of the record.
 *
 * @return mixed The ID of the record.
 */
/**
 * Sets the ID of the record.
 *
 * @param mixed $id The new ID of the record.
 */
/**
 * Retrieves the name of the record.
 *
 * @return string The name of the record.
 */
/**
 * Sets the name of the record.
 *
 * @param string $name The new name of the record.
 */
