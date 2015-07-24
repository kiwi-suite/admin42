<?php
namespace Admin42\Link\Adapter;

interface AdapterInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function assemble($value);

    /**
     * @param mixed $value
     * @return string
     */
    public function getDisplayName($value);
}