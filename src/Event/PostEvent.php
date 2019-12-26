<?php
/**
 * Created by PhpStorm.
 * User: phungduong
 * Date: 2019-12-17
 * Time: 13:44
 */

namespace App\Event;

use App\Entity\Post;
use Symfony\Component\EventDispatcher\Event;


class PostEvent extends Event
{
    public const VIEW = 'post.view';

    protected $post;
    protected $ip;

    public function __construct(Post $post, string $ip = '')
    {
        $this->post = $post;
        $this->ip = $ip;
    }


    public function getPost()
    {
        return $this->post;
    }

    public function getIp()
    {
        return $this->ip;
    }

}