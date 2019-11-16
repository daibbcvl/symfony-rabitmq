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


}