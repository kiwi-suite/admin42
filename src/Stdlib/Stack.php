<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\Stdlib;

class Stack
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * Stack constructor.
     * @param $id
     * @param $type
     * @param array $values
     */
    public function __construct($id, $type, array $values)
    {
        $this->id = $id;
        $this->type = $type;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param $method
     * @param $params
     * @throws \Exception
     * @return mixed
     */
    public function __call($method, $params)
    {
        $variableName = \lcfirst(\mb_substr($method, 3));
        if (\strncasecmp($method, 'get', 3) === 0) {
            if (\array_key_exists($variableName, $this->values)) {
                return $this->values[$variableName];
            }
        }

        throw new \Exception("Method {$method} not found");
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->values[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return \array_key_exists($name, $this->values);
    }
}
