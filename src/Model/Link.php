<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

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
    protected $properties = [
        'id',
        'hash',
        'type',
        'value',
    ];
}
