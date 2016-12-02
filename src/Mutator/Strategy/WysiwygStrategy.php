<?php
namespace Admin42\Mutator\Strategy;

use Admin42\Link\LinkProvider;
use Admin42\Stdlib\Wysiwyg;
use Core42\Hydrator\Mutator\Strategy\StrategyInterface;

class WysiwygStrategy implements StrategyInterface
{

    /**
     * @var LinkProvider
     */
    private $linkProvider;

    /**
     * LinkStrategy constructor.
     * @param LinkProvider $linkProvider
     */
    public function __construct(
        LinkProvider $linkProvider
    ) {
        $this->linkProvider = $linkProvider;
    }

    /**
     * @param mixed $value
     * @param array $spec
     * @return mixed
     */
    public function hydrate($value, array $spec = [])
    {
        return new Wysiwyg($this->linkProvider, $value);
    }
}
