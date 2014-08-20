<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\DataTable\Mutator;

class DateTimeMutator
{
    public function __invoke($value, $row)
    {
        if (!($value instanceof \DateTime)) {
            return $value;
        }

        return strftime("%a. %e. %b. %Y %H:%M:%S", $value->getTimestamp());
    }
}
