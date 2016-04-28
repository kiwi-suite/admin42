<?php
namespace Admin42\Model;

use Core42\Model\AbstractModel;

/**
 * @method Tag setId() setId(int $id)
 * @method int getId() getId()
 * @method Tag setTag() setTag(string $tag)
 * @method string getTag() getTag()
 * @method Tag setNamespace() setNamespace(string $namespace)
 * @method string getNamespace() getNamespace()
 * @method Tag setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
 */
class Tag extends AbstractModel
{

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'tag',
        'namespace',
        'created',
    ];
}
