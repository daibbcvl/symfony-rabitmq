<?php
/**
 * Created by PhpStorm.
 * User: phungduong
 * Date: 2019-11-16
 * Time: 10:06
 */

namespace App\Model;


class DomesticCategory
{
    /** @var string */
    private $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /** @var array */
    private $items;

    /**
     * @return null|array
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * DomesticCategory constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param $item
     *
     * @return $this
     */
    function addItem($item)
    {
        $this->items[] =  $item;

        return $this;
    }



}