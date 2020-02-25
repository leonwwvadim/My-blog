<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 */
class Articles
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Введите развание вашей статьи!")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Вы не можете опубликовать пустую статью!")
     */
    private $artText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCr;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUp;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tags", mappedBy="articles", cascade={"persist"})
     * @OrderBy({"name"="ASC"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", orphanRemoval=true)
     * @OrderBy({"id"="DESC"})
     */
    private $comments;



    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }


    /**
     * @return Collection
     */
    public function getTags() : Collection
    {
        return $this->tags;
    }

    /**
     * @param Tags $tag
     * @return Articles
     */
    public function addTag(Tags $tag): self
    {
        if (! $this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addArticle( $this );
        }
        return $this ;
    }

    /**
     * @param Tags $tag
     * @return Articles
     */
    public function removeTag(Tags $tag): self
    {
        if ( $this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeArticle($this);
        }
        return $this ;
    }

    /**
     * @return $this
     */
    public function removeAllTag(): self
    {
        if(!empty($this->tags)){

            foreach ($this->tags as $tag) {
                $this->removeTag($tag);
           }
        }
        return $this ;
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

    public function getArtText(): ?string
    {
        return $this->artText;
    }

    public function setArtText(string $artText): self
    {
        $this->artText = $artText;

        return $this;
    }

    public function getDateCr(): ?\DateTimeInterface
    {
        return $this->dateCr;
    }

    public function setDateCr(\DateTimeInterface $dateCr): self
    {
        $this->dateCr = $dateCr;

        return $this;
    }

    public function getDateUp(): ?\DateTimeInterface
    {
        return $this->dateUp;
    }

    public function setDateUp(?\DateTimeInterface $dateUp): self
    {
        $this->dateUp = $dateUp;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }


}
