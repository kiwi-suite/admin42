<?php
namespace Admin42\Stdlib;

use Admin42\Link\LinkProvider;

class Link
{
    /**
     * @var LinkProvider
     */
    private $linkProvider;

    /**
     * @var int
     */
    private $id;

    /**
     * Link constructor.
     * @param LinkProvider $linkProvider
     * @param int $id
     */
    public function __construct(LinkProvider $linkProvider, $id)
    {
        $this->linkProvider = $linkProvider;
        $this->id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        if (empty($this->id)) {
            return null;
        }

        $link = $this->linkProvider->getLink($this->id);

        if (empty($link)) {
            return null;
        }

        return $link->getType();
    }

    /**
     * @return string
     */
    public function assemble()
    {
        return $this->linkProvider->assembleById($this->id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->assemble();
    }
}
