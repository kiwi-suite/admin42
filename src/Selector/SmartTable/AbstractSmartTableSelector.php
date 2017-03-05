<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Admin42\Selector\SmartTable;

use Core42\Db\ResultSet\ResultSet;
use Core42\Selector\AbstractDatabaseSelector;
use Core42\View\Model\JsonModel;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Where;
use Zend\Json\Json;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

abstract class AbstractSmartTableSelector extends AbstractDatabaseSelector
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
     * @throws \Exception
     */
    protected function init()
    {
        parent::init();

        if (!\is_array($this->getSearchAbleColumns())) {
            throw new \Exception("'getSearchAbleColumns' doesn't return an array");
        }
        if (!\is_array($this->getSortAbleColumns())) {
            throw new \Exception("'getSortAbleColumns' doesn't return an array");
        }
        if (!\is_array($this->getDisplayColumns())) {
            throw new \Exception("'getDisplayColumns' doesn't return an array");
        }

        $request = $this->getServiceManager()->get('Request');

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
            && (\in_array($config['sort']['predicate'], $this->getSortAbleColumns()))
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
    abstract protected function getSearchAbleColumns();

    /**
     * @return array
     */
    abstract protected function getSortAbleColumns();

    /**
     * @return array
     */
    abstract protected function getDisplayColumns();

    /**
     * @return array
     */
    protected function getOrder()
    {
        if ($this->sort !== null) {
            return [
                $this->sort['column'] => $this->sort['sort_sequence'],
            ];
        }

        return [];
    }

    /**
     * @return null|PredicateSet
     */
    protected function getWhere()
    {
        if (!\is_array($this->search)) {
            return;
        }

        $searchAbleColumns = $this->getSearchAbleColumns();

        $searchWhere = [];
        foreach ($this->search as $column => $value) {
            if ($column == '$') {
                $globalWhere = [];
                foreach ($searchAbleColumns as $_column) {
                    $where = new Where();
                    $where->like($_column, '%' . $value . '%');
                    $globalWhere[] = $where;
                }
                $searchWhere[] = new PredicateSet($globalWhere, PredicateSet::COMBINED_BY_OR);
                continue;
            }

            if (\is_array($value)) {
                foreach ($value as $_innerCol => $_innerVal) {
                    if (!\in_array($column . '.' . $_innerCol, $searchAbleColumns)) {
                        continue;
                    }
                    $where = new Where();
                    $where->like($column . '.' . $_innerCol, '%' . $_innerVal . '%');
                    $searchWhere[] = $where;
                }

                continue;
            }

            if (!\in_array($column, $searchAbleColumns)) {
                continue;
            }
            $where = new Where();
            $where->like($column, '%' . $value . '%');
            $searchWhere[] = $where;
        }

        if (empty($searchWhere)) {
            return;
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

        $paginator = new Paginator(new DbSelect(
            $select,
            $this->getSql(),
            new ResultSet(
                $this->getHydrator(),
                $this->getModel()
            )
        ));
        $paginator->setItemCountPerPage($this->limit);
        $paginator->setCurrentPageNumber(\floor($this->offset / $this->limit) + 1);

        $data = $this->prepareColumns($paginator->getCurrentItems()->getArrayCopy());

        return new JsonModel([
            'data' => $data,
            'meta' => [
                'displayedPages' => $paginator->count(),
            ],
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareColumns(array $data)
    {
        $displayColumns = $this->getDisplayColumns();

        $newData = [];

        foreach ($data as $_item) {
            $itemData = $_item->toArray();
            $keys = \array_keys($itemData);
            foreach ($keys as $_key) {
                if (!\in_array($_key, $displayColumns)) {
                    unset($itemData[$_key]);
                }
            }
            $newData[] = $itemData;
        }

        return $newData;
    }
}
