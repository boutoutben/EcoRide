<?php

namespace App\Entity;

use App\Repository\OpinionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpinionRepository::class)]
class Opinion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500, nullable:true)]
    private ?string $opinion = null;

    #[ORM\Column]
    private ?int $grade = null;

    #[ORM\Column]
    private ?bool $isValid = null;

    #[ORM\Column]
    private ?bool $isGreat = null;

    #[ORM\ManyToOne(inversedBy: 'Opinion')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'driver')]
    private ?User $driver = null;

    #[ORM\ManyToOne(inversedBy: 'carpool')]
    private ?Carpool $carpool = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpinion(): ?string
    {
        return $this->opinion;
    }

    public function setOpinion(string|null $opinion): static
    {
        $this->opinion = $opinion;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->isValid;
    }

    public function setValid(bool $is_valid): static
    {
        $this->isValid = $is_valid;

        return $this;
    }

    public function isGreat(): ?bool
    {
        return $this->isGreat;
    }

    public function setGreat(bool $isGreat): static
    {
        $this->isGreat = $isGreat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getCarpool(): ?Carpool
    {
        return $this->carpool;
    }

    public function setCarpool(?Carpool $carpool): static
    {
        $this->carpool = $carpool;

        return $this;
    }
}
