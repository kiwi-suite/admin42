<?php
namespace Admin42\Model;

use Core42\Model\AbstractModel;

/**
 * @method Tag setId(int $id)
 * @method int getId()
 * @method Tag setTag(string $tag)
 * @method string getTag()
 * @method Tag setNamespace(string $namespace)
 * @method string getNamespace()
 * @method Tag setCreated(\DateTime $created)
 * @method \DateTime getCreated()
 */
class Tag extends AbstractModel
{

    /**
     * @var array
     */
    protected $properties = array(
        'id',
        'tag',
        'namespace',
        'created',
    );

}
