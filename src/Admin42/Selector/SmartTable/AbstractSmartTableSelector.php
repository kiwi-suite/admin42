<?php
namespace Admin42\Selector\SmartTable;

use Core42\Db\ResultSet\ResultSet;
use Core42\Selector\AbstractTableGatewaySelector;
use Core42\View\Model\JsonModel;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Where;
use Zend\Json\Json;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

abstract class AbstractSmartTableSelector extends AbstractTableGatewaySelector
{
    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var null|array
     */
    protected $sort = null;

    /**
     * @var null|array
     */
    protected $search = null;

    /**
     * @var null|array
     */
    protected $searchAbleColumns = null;

    /**
     * @var null|array
     */
    protected $sortAbleColumns = null;

    /**
     *
     */
    protected function init()
    {
        $request = $this->getServiceManager()->get("Request");

        $config = Json::decode($request->getContent(), Json::TYPE_ARRAY);

        if (!empty($config['pagination']['number']) && (int) $config['pagination']['number'] > 0) {
            $this->limit = (int) $config['pagination']['number'];
        }

        if (!empty($config['pagination']['start'])) {
            $this->offset = (int) $config['pagination']['start'];
        }

        if (!empty($config['search']) && !empty($config['search']['predicateObject'])) {
            $this->search = $config['search']['predicateObject'];
        }

        if (!empty($config['sort'])
            && !empty($config['sort']['predicate'])
            && ($config['sort']['reverse'] === true || $config['sort']['reverse'] === false)
            && ($this->sortAbleColumns === null || in_array($config['sort']['predicate'] , $this->sortAbleColumns))
        ) {
            $this->sort = [
                'column' => $config['sort']['predicate'],
                'sort_sequence' => ($config['sort']['reverse'] === true) ? 'DESC' : 'ASC',
            ];
        }

    }

    /**
     * @return array
     */
    protected function getOrder()
    {
        if ($this->sort !== null) {
            return [
                $this->sort['column'] => $this->sort['sort_sequence']
            ];
        }
        return array();
    }

    /**
     * @return null|PredicateSet
     */
    protected function getWhere()
    {
        if (!is_array($this->search)) {
            return null;
        }

        $tableGateway = $this->getTableGateway($this->tableGateway);
        $searchAbleColumns = $this->searchAbleColumns;
        if (empty($searchAbleColumns)) {
            $searchAbleColumns = $tableGateway->getColumns();
        }

        $searchWhere = array();
        foreach ($this->search as $column => $value) {
            if ($column == "$") {
                $globalWhere = array();
                foreach ($searchAbleColumns as $_column) {
                    $where = new Where();
                    $where->like($_column, "%" . $value . "%");
                    $globalWhere[] = $where;
                }
                $searchWhere[] = new PredicateSet($globalWhere, PredicateSet::COMBINED_BY_OR);
                continue;
            }

            if (!in_array($column, $searchAbleColumns)) {
                continue;
            }
            $where = new Where();
            $where->like($column, "%" . $value . "%");
            $searchWhere[] = $where;
        }

        $predicateSet = new PredicateSet($searchWhere, PredicateSet::COMBINED_BY_AND);
        return $predicateSet;
    }

    /**
     * @return JsonModel
     */
    public function getResult()
    {
        $select = $this->getSelect();

        if ($this->columns !== null) {
            $select->columns($this->columns);
        }

        $where = $this->getWhere();
        if (!empty($where)) {
            $select->where($where);
        }

        $order = $this->getOrder();
        if (!empty($order)) {
            $select->order($order);
        }

        $paginator = new Paginator(new DbSelect(
            $select,
            $this->getTableGateway($this->tableGateway)->getSql(),
            new ResultSet(
                $this->getTableGateway($this->tableGateway)->getHydrator(),
                $this->getTableGateway($this->tableGateway)->getModel()
            )
        ));
        $paginator->setItemCountPerPage($this->limit);
        $paginator->setCurrentPageNumber(floor($this->offset/$this->limit) + 1);

        $data = $this->cleanUpColumns($paginator->getCurrentItems()->getArrayCopy());

        return new JsonModel(array(
            'data' => $data,
            'meta' => array(
                'displayedPages' => $paginator->count(),
            )
        ));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function cleanUpColumns(array $data)
    {
        if ($this->columns == null) {
            return $data;
        }

        $newData = array();

        foreach ($data as $_item) {
            $itemData = $_item->toArray();
            $keys = array_keys($itemData);
            foreach ($keys as $_key) {
                if (!in_array($_key, $this->columns)) {
                    unset($itemData[$_key]);
                }
            }
            $newData[] = $itemData;
        }

        return $newData;
    }
}
