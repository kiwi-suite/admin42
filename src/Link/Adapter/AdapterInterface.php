<?php
namespace Admin42\Link\Adapter;

interface AdapterInterface
{
    /**
     * @param mixed $value
     * @param array $options
     * @return string
     */
    public function assemble($value, $options = []);

    /**
     * @param mixed $value
     * @return string
     */
    public function getDisplayName($value);
}
