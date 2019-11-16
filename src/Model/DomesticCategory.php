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

    /** @var string */
    private $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return DomesticCategory
     */
    public function setSlug(string $slug): DomesticCategory
    {
        $this->slug = $slug;
        return $this;
    }

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
     * @param string $slug
     */
    public function __construct(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
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