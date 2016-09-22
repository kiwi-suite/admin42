<?php
namespace Admin42\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;
use DateTime;

class ToDateTime extends AbstractFilter
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        if (is_array($options) || $options instanceof \Traversable) {
            $this->setOptions($options);
        }
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
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
            $value = DateTime::createFromFormat($this->format, $value);
        }
        return $value;
    }
}
