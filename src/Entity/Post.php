<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post implements SoftDeletableInterface, TimestampableInterface
{
    use SoftDeletableTrait, TimestampableTrait;

    const DEFAULT_LANGUAGE = 'vi';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publish;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allowComment = true;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $lang;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $commentCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewerCount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSeo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $keyword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbUrl;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showHomePage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featuredArtitcle;

    function __construct()
    {
        $this->viewerCount = 0;
        $this->commentCount = 0;
        $this->lang = self::DEFAULT_LANGUAGE;
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getAllowComment(): ?bool
    {
        return $this->allowComment;
    }

    public function setAllowComment(bool $allowComment): self
    {
        $this->allowComment = $allowComment;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCommentCount(): ?int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): self
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    public function getViewerCount(): ?int
    {
        return $this->viewerCount;
    }

    public function setViewerCount(int $viewerCount): self
    {
        $this->viewerCount = $viewerCount;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitleSeo(): ?string
    {
        return $this->titleSeo;
    }

    public function setTitleSeo(string $titleSeo): self
    {
        $this->titleSeo = $titleSeo;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(?string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getThumbUrl(): ?string
    {
        return $this->thumbUrl;
    }

    public function setThumbUrl(?string $thumbUrl): self
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getShowHomePage(): ?bool
    {
        return $this->showHomePage;
    }

    public function setShowHomePage(bool $showHomePage): self
    {
        $this->showHomePage = $showHomePage;

        return $this;
    }

    public function getFeaturedArtitcle(): ?bool
    {
        return $this->featuredArtitcle;
    }

    public function setFeaturedArtitcle(bool $featuredArtitcle): self
    {
        $this->featuredArtitcle = $featuredArtitcle;

        return $this;
    }
}
