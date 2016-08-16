<?php
namespace Admin42\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;
use DateTime;

class ToDateTime extends AbstractFilter
{

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (is_int($value)) {
            //timestamp
            $value = new DateTime('@' . $value);
        } elseif (!$value instanceof DateTime) {
            $value = new DateTime($value);
        }

        return $value;
    }
}
