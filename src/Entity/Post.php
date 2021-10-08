<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Post
 * @package App\Entity
 * @ORM\Entity
 */
class Post
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     * @Groups({"get"})
     */
    private ?int $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"get"})
     */
    private string $content;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get"})
     */
    private \DateTimeInterface $publishedAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $author;

    /**
     * @var User[]|Collection
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="post_likes")
     */
    private Collection $likedBy;

    /**
     * @param string $content
     * @param User $author
     * @return static
     */
    public static function createPost(string $content, User $author) :self
    {
        $post = new self();
        $post->content = $content;
        $post->author = $author;

        return $post;
    }

    /**
     * Post constructor
     */
    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
        $this->likedBy = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTimeInterface $publishedAt
     */
    public function setPublishedAt(\DateTimeInterface $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return User[]|Collection
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    /**
     * @param User $user
     */
    public function likedBy(User $user): void
    {
        if ($this->likedBy->contains($user)) {
            return;
        }
        $this->likedBy->add($user);
    }

    /**
     * @param User $user
     */
    public function dislikedBy(User $user): void
    {
        if (!$this->likedBy->contains($user)) {
            return;
        }
        $this->likedBy->removeElement($user);
    }
}