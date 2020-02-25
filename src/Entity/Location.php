<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $loc_x;

    /**
     * @ORM\Column(type="float")
     */
    private $loc_y;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocX(): ?float
    {
        return $this->loc_x;
    }

    public function setLocX(float $loc_x): self
    {
        $this->loc_x = $loc_x;

        return $this;
    }

    public function getLocY(): ?float
    {
        return $this->loc_y;
    }

    public function setLocY(float $loc_y): self
    {
        $this->loc_y = $loc_y;

        return $this;
    }
}
