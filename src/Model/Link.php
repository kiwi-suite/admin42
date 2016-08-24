<?php
namespace Admin42\Model;

use Core42\Model\AbstractModel;

/**
 * @method Link setId() setId(int $id)
 * @method int getId() getId()
 * @method Link setHash() setHash(string $hash)
 * @method string getHash() getHash()
 * @method Link setType() setType(string $type)
 * @method string getType() getType()
 * @method Link setValue() setValue(mixed $value)
 * @method mixed getValue() getValue()
 */
class Link extends AbstractModel
{

    /**
     * @var array
     */
    public $properties = [
        'id',
        'hash',
        'type',
        'value',
    ];
}
