<?php
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
