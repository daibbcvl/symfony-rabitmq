<?php
/**
 * Created by PhpStorm.
 * User: phungduong
 * Date: 2019-11-16
 * Time: 10:06
 */

namespace App\Model;


use App\Entity\Post;

class CategoryItem
{
    /** @var string */
    private $name;

    /**
     * @var string
     */
    private $description;

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
     * @return CategoryItem
     */
    public function setSlug(string $slug): CategoryItem
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
     * @return CategoryItem
     */
    public function setName(string $name): CategoryItem
    {
        $this->name = $name;
        return $this;
    }

    /** @var Post[] */
    private $posts;

    /**
     * @return Post[]\null
     */
    public function getPosts(): ?array
    {
        return $this->posts;
    }

    /**
     * @param Post[] $posts
     * @return CategoryItem
     */
    public function setPosts(?array $posts): CategoryItem
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * CategoryItem constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CategoryItem
     */
    public function setDescription(?string $description): CategoryItem
    {
        $this->description = $description;
        return $this;
    }

}