<?php
namespace Admin42\DataTable;

use Admin42\DataTable\Column\Column;
use Admin42\DataTable\Decorator\DeleteButtonDecorator;
use Admin42\DataTable\Decorator\EditButtonDecorator;
use Admin42\DataTable\Decorator\IdDecorator;
use Zend\Mvc\Router\RouteStackInterface;

class DataTable implements \Countable, \Iterator
{
    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $columns = array();

    /**
     * @var int
     */
    protected $internPosition = 0;

    /**
     * @var array
     */
    protected $defaultDecorators = array();

    /**
     * @var array
     */
    protected $attributes = array(
        'autoWidth' => false,
        'serverSide' => false,
    );

    public function __construct(RouteStackInterface $router)
    {
        $this->router = $router;
        $this->defaultDecorators[] = new IdDecorator();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return Column
     */
    public function current()
    {
        return $this->columns[$this->internPosition];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->internPosition;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->internPosition;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return array_key_exists($this->internPosition, $this->columns);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->internPosition = 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->columns);
    }

    /**
     * @param Column|array $column
     * @throws \Exception
     */
    public function addColumn($column)
    {
        if (is_array($column)) {
            $column = new Column($column);
        }

        if (!($column instanceof Column)) {
            throw new \Exception("no instance of column");
        }

        $this->columns[] = $column;
    }

    /**
     * @param $routeName
     * @param array $matchParams
     * @param array $extraParams
     * @throws \Exception
     */
    public function addEditButton($routeName, array $matchParams, array $extraParams = array())
    {
        $column = new Column();
        $column->setSortable(false);
        $column->setSearchable(false);

        $router = $this->router;

        $column->setMutator(function($value, $row) use ($router, $routeName, $matchParams, $extraParams){

            foreach ($matchParams as $matchName => $nameInRoute) {
                $extraParams[$nameInRoute] = $row[$matchName];
            }

            return array(
                'type' => 'edit',
                'url' => $router->assemble($extraParams, array('name' => $routeName)),
            );
        });

        $column->addDecorator(new EditButtonDecorator());

        $this->addColumn($column);
    }

    public function addDeleteButton($routeName, array $matchParams)
    {
        $column = new Column();
        $column->setSortable(false);
        $column->setSearchable(false);

        $router = $this->router;
        $column->setMutator(function($value, $row) use($router, $routeName, $matchParams){
            $deleteParams = array();
            foreach ($matchParams as $matchName => $nameInRoute) {
                $deleteParams[$nameInRoute] = $row[$matchName];
            }

            return array(
                'type' => 'delete',
                'url' => $router->assemble(array(), array('name' => $routeName)),
                'params' => http_build_query($deleteParams),
            );
        });
        $column->addDecorator(new DeleteButtonDecorator());

        $this->addColumn($column);
    }

    /**
     * @param int $position
     * @return Column|null
     */
    public function getColumnAtPosition($position)
    {
        if (!array_key_exists($position, $this->columns)) {
            return null;
        }

        return $this->columns[$position];
    }

    public function setAjax($route, array $extraParams = array())
    {
        $url = $this->router->assemble($extraParams, array('name' => $route));
        $this->addAttribute('serverSide', true)
            ->addAttribute('ajax', array(
                'type' => 'POST',
                'url' => $url
            ));

        return $this;
    }

    /**
     *
     */
    public function applyDecorators()
    {
        foreach ($this as $column) {
            foreach ($this->defaultDecorators as $_decorator) {
                $_decorator($column);
            }
            $column->applyDecorators();
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
