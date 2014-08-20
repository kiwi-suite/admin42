<?php
namespace Admin42\DataTable;

use Admin42\DataTable\Column\Column;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\JsonSerializable;

class ResultSet extends \Core42\Db\ResultSet\ResultSet implements JsonSerializable
{
    /**
     * @var DataTable
     */
    protected $dataTable;

    /**
     * @param DataTable $dataTable
     * @param HydratorInterface $hydrator
     * @param null $objectPrototype
     */
    public function __construct(DataTable $dataTable, HydratorInterface $hydrator = null, $objectPrototype = null)
    {
        $this->dataTable = $dataTable;

        parent::__construct($hydrator, $objectPrototype);
    }

    /**
     * @return array
     */
    protected function extractPublicData()
    {
        $data = array();

        foreach ($this as $row) {
            if (is_array($row)) {

            } elseif (method_exists($row, 'toArray')) {
                $row = $row->toArray();
            } elseif (method_exists($row, 'getArrayCopy')) {
                $row = $row->getArrayCopy();
            } else {
                continue;
            }

            $rowReturn = array();
            /** @var Column $column */
            foreach ($this->dataTable as $column) {
                $value = null;
                if ($column->getMatchName()) {
                    $value = $row[$column->getMatchName()];
                }

                if ($column->getMutator()) {
                    $value = call_user_func($column->getMutator(), $value, $row);
                }

                $rowReturn[] = $value;
            }

            $data[] = $rowReturn;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->extractPublicData();
    }

    /**
     * @return array
     */
    public function toJson()
    {
        return $this->extractPublicData();
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return $this->extractPublicData();
    }
}
