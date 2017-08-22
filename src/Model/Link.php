<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
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
