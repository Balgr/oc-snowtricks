<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraint as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickImageRepository")
 */
class TrickImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="trickImages")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $trick;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $imagePath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function setImagePath($imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function __toString()
    {
        return $this->imagePath;
    }
}
