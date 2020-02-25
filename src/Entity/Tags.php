<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagsRepository")
 */
class Tags
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Articles", inversedBy="tags", cascade={"persist"})
     * @ORM\JoinTable(name="tags_articles")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return Collection
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @param Articles $article
     * @return Tags
     */
    public function addArticle(Articles $article): self
    {
        if (! $this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addTag( $this );
        }
        return $this ;
    }

    /**
     * @param Articles $article
     * @return Tags
     */
    public function removeArticle(Articles $article): self
    {
        if ( $this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeTag( $this );
        }
        return $this ;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
