<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\DataTable;

use Admin42\DataTable\Column\Column;
use Core42\Selector\AbstractDatabaseSelector;
use Core42\View\Model\JsonModel;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Parameters;

abstract class AbstractDataTableSelector extends AbstractDatabaseSelector
{
    /**
     * @var DataTable
     */
    protected $dataTable;

    /**
     *
     */
    protected function init()
    {
        $dataTable = $this->getServiceManager()->get('Admin42\DataTable');
        $this->configure($dataTable);
        $this->dataTable = $dataTable;
    }

    /**
     * @param DataTable $dataTable
     */
    abstract protected function configure(DataTable $dataTable);

    /**
     * @return DataTable
     */
    public function getDataTable()
    {
        return $this->dataTable;
    }

    /**
     * @return Parameters
     */
    protected function getPostData()
    {
        return $this->getServiceManager()->get('request')->getPost();
    }

    /**
     * @return int
     */
    protected function getOffset()
    {
        return (int) $this->getPostData()->get('start', 0);
    }

    /**
     * @return int
     */
    protected function getLimit()
    {
        $limit = (int) $this->getPostData()->get('length', 10);
        $limit = !empty($limit) ? $limit : 10;

        return $limit;
    }

    /**
     * @return null|Where|\Closure|string|array
     */
    protected function getWhere()
    {
        $globalSearch = $this->getPostData()->get('search', array());
        $globalSearch = (!is_array($globalSearch) || empty($globalSearch['value'])) ? "" : $globalSearch['value'];

        $postColumns = $this->getPostData()->get('columns', array());
        $postColumns = (is_array($postColumns)) ? $postColumns : array();

        $tmpSearch = array();

        foreach ($postColumns as $_postColumn) {
            $search = (!empty($_postColumn['search']['value'])) ? $_postColumn['search']['value'] : $globalSearch;
            $search = trim($search);

            if (empty($search)) {
                continue;
            }

            $position = (array_key_exists('data', $_postColumn) && ctype_digit($_postColumn['data'])) ? (int)$_postColumn['data'] : false;

            /** @var Column $column */
            $column = $this->dataTable->getColumnAtPosition($position);
            if (empty($column)) {
                continue;
            }

            if (!$column->isSearchable() || $column->getMatchName() === null) {
                continue;
            }


            $searchParts = explode(" ", $search);
            foreach ($searchParts as $_part) {
                $_part = trim($_part);
                $tmpSearch[$_part][] = new Like($column->getMatchName(), '%'.$_part.'%');
            }
        }

        if (empty($tmpSearch)) {
            return null;
        }

        $searchWhere = array();
        foreach ($tmpSearch as $columnSeparatedLikes) {
            $columnSeparatedLikes = array_values($columnSeparatedLikes);
            if (!empty($columnSeparatedLikes)) {
                $searchWhere[] = new PredicateSet($columnSeparatedLikes, PredicateSet::COMBINED_BY_OR);
            }
        }

        if (empty($searchWhere)) {
            return null;
        }

        return array(new PredicateSet($searchWhere, PredicateSet::COMBINED_BY_AND));
    }

    /**
     * @return null|string|array
     */
    protected function getOrder()
    {
        $order = $this->getPostData()->get('order', array());
        if (!is_array($order) || empty($order)) {
            return null;
        }

        $orderResult = array();

        foreach ($order as $orderOption) {
            if (!array_key_exists("column", $orderOption) || !array_key_exists("dir", $orderOption)) {
                continue;
            }

            if (!in_array($orderOption['dir'], array('asc', 'desc'))) {
                continue;
            }

            $columnPosition = (int) $orderOption['column'];
            $column = $this->dataTable->getColumnAtPosition($columnPosition);
            if (empty($column)) {
                continue;
            }

            if ($column->isSortable() === false) {
                continue;
            }

            if (strlen($column->getMatchName()) == 0) {
                continue;
            }

            $orderResult[] = "{$column->getMatchName()} {$orderOption['dir']}";
        }

        if (empty($orderResult)) {
            return null;
        }

        return $orderResult;
    }

    /**
     * @return JsonModel
     * @throws \Exception
     */
    public function getResult()
    {
        $select = $this->getSelect();

        if (!($select instanceof Select)) {
            throw new \Exception("getSelect() must return an instance of 'Zend\\Db\\Sql\\Select'");
        }

        if ($where = $this->getWhere()) {
            $select->where($where);
        }
        if ($order = $this->getOrder()) {
            $select->order($order);
        }

        $paginator = new Paginator(
            new DbSelect(
                $select,
                $this->getSql(),
                new ResultSet($this->dataTable, $this->getHydrator(), $this->getModel())
            )
        );
        $paginator->setItemCountPerPage($this->getLimit());
        $paginator->setCurrentPageNumber(floor($this->getOffset()/$this->getLimit()) + 1);

        return new JsonModel(array(
            'draw' => (int) $this->getPostData()->get('draw', 0),
            'recordsTotal' => $paginator->getTotalItemCount(),
            'recordsFiltered' => $paginator->getTotalItemCount(),
            'data' => $paginator->getCurrentItems(),
        ));
    }
}
