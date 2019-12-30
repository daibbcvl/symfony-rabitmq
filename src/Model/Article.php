<?php
/**
 * Created by PhpStorm.
 * User: phungduong
 * Date: 2019-11-16
 * Time: 10:06
 */

namespace App\Model;


use App\Entity\Post;

class Article
{
    /**
     * @var
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $thumbUrl;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var boolean
     */
    private $allowComment;

    /**
     * @var int
     */
    private $commentCount;

    /**
     * @var int
     */
    private $viewerCount;

    /**
     * @var string
     */
    private $titleSeo;

    /**
     * @var string
     */
    private $meta;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var string
     */
    private $publishedAt;

    /**
     * @var CategoryItem
     */
    private $category;

    /** @var Tag[] */
    private $tags;

    /**
     * Article constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->id = $post->getId();
        $this->title = $post->getTitle();
        $this->summary = $post->getSummary();
        $this->thumbUrl = $post->getThumbUrl();
        $this->content = $post->getContent();
        $this->lang = $post->getLang();
        $this->allowComment = $post->getAllowComment();
        $this->commentCount = $post->getCommentCount();
        $this->viewerCount = $post->getViewerCount();
        $this->titleSeo = $post->getTitleSeo();
        $this->meta = $post->getMeta();
        $this->keyword = $post->getKeyword();
        $this->publishedAt = $post->getPublishedAt() ? $post->getPublishedAt()->format('Y-m-d h:s:i') : null;
        $this->slug = $post->getSlug();

        $this->category = $post->getCategory() ? new CategoryItem($post->getCategory()->getName()) : null;
        if($post->getCategory()){
            $this->category->setSlug($post->getCategory()->getCategorySlug());
        }

        foreach ($post->getTags() as $tag) {
            $tagItem = new Tag();
            $tagItem->setName($tag->getName())->setSlug($tag->getTagSlug());
            $this->tags[] = $tagItem;
        }
    }

    public function minimizeAttributes($keep = [])
    {
        $this->content = null;
        if(!isset($keep['summary'])){
            $this->summary = null;
        }
        $this->meta = null;
        $this->keyword = null;
        $this->titleSeo = null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Article
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Article
     */
    public function setTitle(string $title): Article
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return Article
     */
    public function setSummary(?string $summary): Article
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbUrl(): ?string
    {
        return $this->thumbUrl;
    }

    /**
     * @param string $thumbUrl
     * @return Article
     */
    public function setThumbUrl(?string $thumbUrl): Article
    {
        $this->thumbUrl = $thumbUrl;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return Article
     */
    public function setContent(?string $content): Article
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     * @return Article
     */
    public function setLang(string $lang): Article
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowComment(): bool
    {
        return $this->allowComment;
    }

    /**
     * @param bool $allowComment
     * @return Article
     */
    public function setAllowComment(bool $allowComment): Article
    {
        $this->allowComment = $allowComment;
        return $this;
    }

    /**
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * @param int $commentCount
     * @return Article
     */
    public function setCommentCount(int $commentCount): Article
    {
        $this->commentCount = $commentCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getViewerCount(): int
    {
        return $this->viewerCount;
    }

    /**
     * @param int $viewerCount
     * @return Article
     */
    public function setViewerCount(int $viewerCount): Article
    {
        $this->viewerCount = $viewerCount;
        return $this;
    }

    /**
     * @return string|null;
     */
    public function getTitleSeo(): ?string
    {
        return $this->titleSeo;
    }

    /**
     * @param string $titleSeo
     * @return Article
     */
    public function setTitleSeo(?string $titleSeo): Article
    {
        $this->titleSeo = $titleSeo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMeta(): ?string
    {
        return $this->meta;
    }

    /**
     * @param string $meta
     * @return Article
     */
    public function setMeta(?string $meta): Article
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     * @return Article
     */
    public function setKeyword(?string $keyword): Article
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublishedAt(): ?string
    {
        return $this->publishedAt;
    }

    /**
     * @param string $publishedAt
     * @return Article
     */
    public function setPublishedAt(string $publishedAt): Article
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    /**
     * @return CategoryItem
     */
    public function getCategory(): ?CategoryItem
    {
        return $this->category;
    }

    /**
     * @param CategoryItem $category
     * @return Article
     */
    public function setCategory(CategoryItem $category): Article
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param Tag[]|null $tags
     * @return Article
     */
    public function setTags(?array $tags): Article
    {
        $this->tags = $tags;
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
     * @return Article
     */
    public function setSlug(string $slug): Article
    {
        $this->slug = $slug;
        return $this;
    }

}