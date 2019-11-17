<?php
/**
 * Created by PhpStorm.
 * User: phungduong
 * Date: 2019-11-16
 * Time: 10:06
 */

namespace App\Model;


use App\Entity\Post;

class Tag
{
    /** @var string */
    private $name;

    /** @var string */
    private $slug;


    /** @var Post[] */
    private $posts;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function setName(string $name): Tag
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Tag
     */
    public function setSlug(string $slug): Tag
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return Post[]|null
     */
    public function getPosts(): ?array
    {
        return $this->posts;
    }

    /**
     * @param Post[]|null $posts
     * @return Tag
     */
    public function setPosts(?array $posts): Tag
    {
        $this->posts = $posts;
        return $this;
    }


}