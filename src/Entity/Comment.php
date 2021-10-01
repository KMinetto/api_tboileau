<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment
 * @package App\Entity
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private string $message;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeInterface $publishedAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $author;

    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post")
     */
    private Post $post;

    /**
     * Comment constructor
     */
   public function __construct()
   {
       $this->publishedAt = new \DateTimeImmutable();
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }
}