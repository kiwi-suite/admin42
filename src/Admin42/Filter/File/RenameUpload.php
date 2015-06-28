<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Filter\File;

class RenameUpload extends \Zend\Filter\File\RenameUpload
{
    /**
     * @param array|string $value
     * @return array|string
     */
    public function filter($value)
    {
        if (!is_dir($this->getTarget())) {
            mkdir($this->getTarget(), 0777, true);
        }

        return parent::filter($value);
    }
}
